$( document ).ready( function() {
    // handle forward/back buttons
    $( 'form' ).on( 'click', '.next, .back', function( event ) {
        event.preventDefault();
    });
    $( 'form' ).on( 'click', '.next', function( event ) {
        var $formHeight = $( '.signup' ).height();
        var $thisHeight= $( this ).parent( 'fieldset' ).height();
        var $nextHeight = $( this ).parent( 'fieldset' ).next().actual( 'height' );
        $( '.signup' ).animate( { height: $formHeight - $thisHeight + $nextHeight } );
        $( this ).parent( 'fieldset' ).fadeOut(300, function() {
            $( this ).next().fadeIn(300);
            var $firstInput = $( this ).next().children( 'input' ).first();
            $( 'html, body' ).animate({
                scrollTop: ( $firstInput.offset().top - 32 )
            }, 300, function() {
                $firstInput.focus();
            });
        });
    });
    $( 'form' ).on( 'click', '.back', function( event ) {
        var $formHeight = $( '.signup' ).height();
        var $thisHeight= $( this ).parent( 'fieldset' ).height();
        var $prevHeight = $( this ).parent( 'fieldset' ).prev().actual( 'height' );
        $( '.signup' ).animate( { height: $formHeight - $thisHeight + $prevHeight } );
        $( this ).parent( 'fieldset' ).fadeOut(300, function() {
            $( this ).prev().fadeIn();
            var $firstInput = $( this ).prev().children( 'input' ).first();
            $( 'html, body' ).animate({
                scrollTop: ( $firstInput.offset().top - 32 )
            }, 300, function() {
                $firstInput.focus();
            });
        });
    });

    // add tabindex to all inputs, ordering back buttons after next
    $( ":input" ).each( function(i) {
        $( this ).attr( 'tabindex', i + 1 );
    });
    $( ":input.back" ).each( function(j) {
        $(this).attr('tabindex', +( $( this ).attr( 'tabindex' ) ) + 1);
    });
    $( ":input.next, :input[type='submit']").each( function(j) {
        $( this ).attr( 'tabindex', +( $( this ).attr( 'tabindex' ) ) - 1);
    });

    // submit form via AJAX
    $( 'form.signup' ).append( '<input type="hidden" name="ajax" value="true">' );
    $( 'form.signup' ).on( 'submit', function() {

        // add loading class
        $( '[type="submit"]' ).html( 'Loading&hellip; <span class="spinner"></span>' );

        // prevent default form submission
        event.preventDefault();

        // get all form data
        var formData = $( 'form.signup' ).serializeArray();

        // submit via ajax
        $.ajax({
            type: "POST",
            url: "lib/process_form.php",
            data: formData,
            dataType: "json",
        }).done( function( data ) {
            // replace last fieldset with thank-you message
            $( 'form.signup' ).fadeOut(300, function() {
                $( '.message.success' ).fadeIn();
            });
            console.log( data );
            console.log( 'Form submission successful' );
        }).fail( function( data ) {
            var $responseText = $.parseJSON( data['responseText'] );
            // go back to form, display error message
            $( 'form.signup' ).fadeOut(300, function() {
                $( '.message.failure' ).fadeIn().append( '<p><strong>Error message:</strong> ' + $responseText['faultString'] + '</p>' );
            });
            console.log( data );
            console.log( 'Form submission failed' );
        });
    });
});

// from https://github.com/dreamerslab/jquery.actual/
/*! Copyright 2012, Ben Lin (http://dreamerslab.com/)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Version: 1.0.16
 *
 * Requires: jQuery >= 1.2.3
 */
;( function ( factory ) {
if ( typeof define === 'function' && define.amd ) {
    // AMD. Register module depending on jQuery using requirejs define.
    define( ['jquery'], factory );
} else {
    // No AMD.
    factory( jQuery );
}
}( function ( $ ){
  $.fn.addBack = $.fn.addBack || $.fn.andSelf;

  $.fn.extend({

    actual : function ( method, options ){
      // check if the jQuery method exist
      if( !this[ method ]){
        throw '$.actual => The jQuery method "' + method + '" you called does not exist';
      }

      var defaults = {
        absolute      : false,
        clone         : false,
        includeMargin : false,
        display       : 'block'
      };

      var configs = $.extend( defaults, options );

      var $target = this.eq( 0 );
      var fix, restore;

      if( configs.clone === true ){
        fix = function (){
          var style = 'position: absolute !important; top: -1000 !important; ';

          // this is useful with css3pie
          $target = $target.
            clone().
            attr( 'style', style ).
            appendTo( 'body' );
        };

        restore = function (){
          // remove DOM element after getting the width
          $target.remove();
        };
      }else{
        var tmp   = [];
        var style = '';
        var $hidden;

        fix = function (){
          // get all hidden parents
          $hidden = $target.parents().addBack().filter( ':hidden' );
          style   += 'visibility: hidden !important; display: ' + configs.display + ' !important; ';

          if( configs.absolute === true ) style += 'position: absolute !important; ';

          // save the origin style props
          // set the hidden el css to be got the actual value later
          $hidden.each( function (){
            // Save original style. If no style was set, attr() returns undefined
            var $this     = $( this );
            var thisStyle = $this.attr( 'style' );

            tmp.push( thisStyle );
            // Retain as much of the original style as possible, if there is one
            $this.attr( 'style', thisStyle ? thisStyle + ';' + style : style );
          });
        };

        restore = function (){
          // restore origin style values
          $hidden.each( function ( i ){
            var $this = $( this );
            var _tmp  = tmp[ i ];

            if( _tmp === undefined ){
              $this.removeAttr( 'style' );
            }else{
              $this.attr( 'style', _tmp );
            }
          });
        };
      }

      fix();
      // get the actual value with user specific methed
      // it can be 'width', 'height', 'outerWidth', 'innerWidth'... etc
      // configs.includeMargin only works for 'outerWidth' and 'outerHeight'
      var actual = /(outer)/.test( method ) ?
        $target[ method ]( configs.includeMargin ) :
        $target[ method ]();

      restore();
      // IMPORTANT, this plugin only return the value of the first element
      return actual;
    }
  });
}));
