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

    // validate credit card number
    $( '#cardnumber' ).validateCreditCard(function( result ) {
        var cardName = result.card_type.name || '';
        $( '#cardnumber' ).removeClass().addClass( cardName );

        if ( result.length_valid ) {
            $( '#cardnumber' ).addClass( 'valid-length' );
        } else {
            $( '#cardnumber' ).removeClass( 'valid-length' );
        }

        if ( result.valid ) {
            $( '#cardnumber' ).addClass( 'valid' );
        } else {
            $( '#cardnumber' ).addClass( 'invalid' );
        }
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

// Generated by CoffeeScript 1.8.0

// from https://github.com/PawelDecowski/jQuery-CreditCardValidator/
/*
jQuery Credit Card Validator 1.0

Copyright 2012-2015 Pawel Decowski
 */

(function() {
  var $,
    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  $ = jQuery;

  $.fn.validateCreditCard = function(callback, options) {
    var bind, card, card_type, card_types, get_card_type, is_valid_length, is_valid_luhn, normalize, validate, validate_number, _i, _len, _ref;
    card_types = [
      {
        name: 'amex',
        pattern: /^3[47]/,
        valid_length: [15]
      }, {
        name: 'diners_club_carte_blanche',
        pattern: /^30[0-5]/,
        valid_length: [14]
      }, {
        name: 'diners_club_international',
        pattern: /^36/,
        valid_length: [14]
      }, {
        name: 'jcb',
        pattern: /^35(2[89]|[3-8][0-9])/,
        valid_length: [16]
      }, {
        name: 'laser',
        pattern: /^(6304|670[69]|6771)/,
        valid_length: [16, 17, 18, 19]
      }, {
        name: 'visa_electron',
        pattern: /^(4026|417500|4508|4844|491(3|7))/,
        valid_length: [16]
      }, {
        name: 'visa',
        pattern: /^4/,
        valid_length: [16]
      }, {
        name: 'mastercard',
        pattern: /^5[1-5]/,
        valid_length: [16]
      }, {
        name: 'maestro',
        pattern: /^(5018|5020|5038|6304|6759|676[1-3])/,
        valid_length: [12, 13, 14, 15, 16, 17, 18, 19]
      }, {
        name: 'discover',
        pattern: /^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/,
        valid_length: [16]
      }
    ];
    bind = false;
    if (callback) {
      if (typeof callback === 'object') {
        options = callback;
        bind = false;
        callback = null;
      } else if (typeof callback === 'function') {
        bind = true;
      }
    }
    if (options == null) {
      options = {};
    }
    if (options.accept == null) {
      options.accept = (function() {
        var _i, _len, _results;
        _results = [];
        for (_i = 0, _len = card_types.length; _i < _len; _i++) {
          card = card_types[_i];
          _results.push(card.name);
        }
        return _results;
      })();
    }
    _ref = options.accept;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      card_type = _ref[_i];
      if (__indexOf.call((function() {
        var _j, _len1, _results;
        _results = [];
        for (_j = 0, _len1 = card_types.length; _j < _len1; _j++) {
          card = card_types[_j];
          _results.push(card.name);
        }
        return _results;
      })(), card_type) < 0) {
        throw "Credit card type '" + card_type + "' is not supported";
      }
    }
    get_card_type = function(number) {
      var _j, _len1, _ref1;
      _ref1 = (function() {
        var _k, _len1, _ref1, _results;
        _results = [];
        for (_k = 0, _len1 = card_types.length; _k < _len1; _k++) {
          card = card_types[_k];
          if (_ref1 = card.name, __indexOf.call(options.accept, _ref1) >= 0) {
            _results.push(card);
          }
        }
        return _results;
      })();
      for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
        card_type = _ref1[_j];
        if (number.match(card_type.pattern)) {
          return card_type;
        }
      }
      return null;
    };
    is_valid_luhn = function(number) {
      var digit, n, sum, _j, _len1, _ref1;
      sum = 0;
      _ref1 = number.split('').reverse();
      for (n = _j = 0, _len1 = _ref1.length; _j < _len1; n = ++_j) {
        digit = _ref1[n];
        digit = +digit;
        if (n % 2) {
          digit *= 2;
          if (digit < 10) {
            sum += digit;
          } else {
            sum += digit - 9;
          }
        } else {
          sum += digit;
        }
      }
      return sum % 10 === 0;
    };
    is_valid_length = function(number, card_type) {
      var _ref1;
      return _ref1 = number.length, __indexOf.call(card_type.valid_length, _ref1) >= 0;
    };
    validate_number = (function(_this) {
      return function(number) {
        var length_valid, luhn_valid;
        card_type = get_card_type(number);
        luhn_valid = false;
        length_valid = false;
        if (card_type != null) {
          luhn_valid = is_valid_luhn(number);
          length_valid = is_valid_length(number, card_type);
        }
        return {
          card_type: card_type,
          valid: luhn_valid && length_valid,
          luhn_valid: luhn_valid,
          length_valid: length_valid
        };
      };
    })(this);
    validate = (function(_this) {
      return function() {
        var number;
        number = normalize($(_this).val());
        return validate_number(number);
      };
    })(this);
    normalize = function(number) {
      return number.replace(/[ -]/g, '');
    };
    if (!bind) {
      return validate();
    }
    this.on('input.jccv', (function(_this) {
      return function() {
        $(_this).off('keyup.jccv');
        return callback.call(_this, validate());
      };
    })(this));
    this.on('keyup.jccv', (function(_this) {
      return function() {
        return callback.call(_this, validate());
      };
    })(this));
    callback.call(this, validate());
    return this;
  };

}).call(this);
