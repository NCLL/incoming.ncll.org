$( document ).ready( function() {
    $( 'form' ).on( 'click', '.next, .back', function( event ) {
        event.preventDefault();
    });
    $( 'form' ).on( 'click', '.next', function( event ) {
        var $formHeight = $( '.signup' ).height();
        var $thisHeight= $( this ).parent( 'fieldset' ).height();
        var $nextHeight = $( this ).parent( 'fieldset' ).next().actual( 'height' );
        $( '.signup' ).animate( { height: $formHeight - $thisHeight + $nextHeight } );
        $( this ).parent( 'fieldset' ).fadeOut(300, function() {
            $( this ).next().fadeIn();
        });
    });
    $( 'form' ).on( 'click', '.back', function( event ) {
        var $formHeight = $( '.signup' ).height();
        var $thisHeight= $( this ).parent( 'fieldset' ).height();
        var $prevHeight = $( this ).parent( 'fieldset' ).prev().actual( 'height' );
        $( '.signup' ).animate( { height: $formHeight - $thisHeight + $prevHeight } );
        $( this ).parent( 'fieldset' ).fadeOut(300, function() {
            $( this ).prev().fadeIn();
        });
    });
});
