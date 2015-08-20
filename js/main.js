$( document ).ready( function() {
    $( 'form' ).on( 'click', '.next, .back', function( event ) {
        event.preventDefault();
    });
    $( 'form' ).on( 'click', '.next', function( event ) {
        $( this ).parent( 'fieldset' ).fadeOut(400, function() {
            $(this).next().fadeIn();
        });
    });
    $( 'form' ).on( 'click', '.back', function( event ) {
        $( this ).parent( 'fieldset' ).fadeOut(400, function() {
            $(this).prev().fadeIn();
        });
    });
});
