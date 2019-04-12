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

    config.toolbarGroups = [
        { name: 'document',    groups: [ 'mode'] },
        //{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'tools' },
        '/',
        { name: 'colors' },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'links' },
        { name: 'insert' },

        { name: 'paragraph',   groups: [ 'list', 'blocks', 'align' ] },
        { name: 'styles' },


        { name: 'others' },
        { name: 'about' }
    ];

/*
    config.toolbar  = [
        { name: 'document',    items: ['Syntaxhighlight', 'Maximize', 'Source', '-', 'Preview', '-', 'Templates', '-', 'RemoveFormat'] },
        { name: 'clipboard',   items: [ 'Find', 'Scayt', '-', 'PasteText', '-', 'Undo', 'Redo' ] },
        { name: 'links',       items: ['Link', 'Unlink', '-', 'Image', 'Youtube', 'Flash', 'Smiley'] },
        { name: 'media',       items: ['Table', 'SpecialChar', 'Iframe', 'InsertPre', '-', 'Textarea', 'TextField', '-', 'NumberedList', 'BulletedList', 'Blockquote'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor'] },
        { name: 'align',       items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Outdent', 'Indent'] },
        { name: 'styles',      items: ['Styles', 'Format', 'Font', 'FontSize'] }
    ];
*/
};