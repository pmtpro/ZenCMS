$("#list-images").on('mouseover', function() {
    var client = new ZeroClipboard($('.copy-short-url'));
    client.on( "ready", function( readyEvent ) {
        client.on( "aftercopy", function( event ) {
            $(event.target).fadeOut(600, function() {
                $(this).text("Đã copy").fadeIn(1200, function() {
                    $(event.target).fadeOut(0, function() {
                        $(this).text("Copy").fadeIn(0);
                    });
                });
            });
        });
    } );
});