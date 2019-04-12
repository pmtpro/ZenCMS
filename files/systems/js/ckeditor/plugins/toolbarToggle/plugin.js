CKEDITOR.plugins.add( 'toolbarToggle', {
    icons: 'toolbarToggle',
    init: function( editor ) {
        editor.addCommand( 'toolbarToggle', {
            exec: function( editor ) {
                editor.focus();
                /*
                var toolbar = document.getElementsByClassName("cke_top");
                toolbar.className = toolbar.className + " cke_top_all";
                */
                if (this.state == CKEDITOR.TRISTATE_OFF) {
                    $('.cke_top').addClass('cke_top_all');
                    this.setState(CKEDITOR.TRISTATE_ON);
                } else {
                    $('.cke_top').removeClass('cke_top_all');
                    this.setState(CKEDITOR.TRISTATE_OFF);
                }
            },
            refresh:function(editor,path){

            }
        });
        editor.ui.addButton( 'ToolbarToggle', {
            label: 'Toolbar toggle',
            command: 'toolbarToggle'
        });
    }
});
