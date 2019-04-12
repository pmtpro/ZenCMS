/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.skin = 'bootstrapck';
    config.height = "600px";
    /*
    config.toolbarGroups = [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'forms' },
        { name: 'tools' },
        { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'others' },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
        { name: 'styles' },
        { name: 'colors' },
        { name: 'about' }
    ];
    */

    config.toolbar  = [
        { name: 'document',    items: ['Syntaxhighlight', 'Maximize', 'Source', '-', 'Preview', '-', 'Templates', '-', 'RemoveFormat'] },
        { name: 'clipboard',   items: [ 'Find', 'Scayt', '-', 'PasteText', '-', 'Undo', 'Redo' ] },
        { name: 'links',       items: ['Link', 'Unlink', '-', 'Image', 'Youtube', 'Flash', 'Smiley'] },
        { name: 'media',       items: ['Table', 'SpecialChar', 'Iframe', 'InsertPre', '-', 'Textarea', 'TextField', '-', 'NumberedList', 'BulletedList', 'Blockquote'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor'] },
        { name: 'align',       items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Outdent', 'Indent'] },
        { name: 'styles',      items: ['Styles', 'Format', 'Font', 'FontSize'] }
    ];
};

CKEDITOR.on( 'dialogDefinition', function( ev )
{
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;
    var dialog = ev.data.definition.dialog;
    if (dialogName == 'image2')
    {
        // Add a new tab to the "Link" dialog.
        //dialogDefinition.addContents();
        /*
        dialog.on('show', function () {
            this.selectPage('Upload');
        });
        */
        /*
        for (var i in dialogDefinition.contents)
        {
            var contents = dialogDefinition.contents[i];
            if (contents.id == "Upload")
            {
                contents.elements.splice(contents.elements.length - 1, 0, {
                    type : 'text',
                    id : 'field1',
                    name: 'field1',
                    label : 'Field 1'
                });
                contents.elements.splice(contents.elements.length - 1, 0, {
                    type : 'html',
                    html: '<input type="text" name="thang" value="abc"/>'
                });
                contents.elements.splice(contents.elements.length - 1, 0, {
                    type : 'text',
                    id : 'field2',
                    label : 'Field 2'
                });
            }
        }
        */
    }

});
