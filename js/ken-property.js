jQuery(function(){

    jQuery('.de-prop-gallery a').live('click', function() {
        jQuery('.de-prop-gallery .main-photo img').remove();

        var img = jQuery('<img>')
                .attr('src', jQuery(this).attr('href'))
                .attr('width', jQuery(this).attr('data-width'))
                .attr('height', jQuery(this).attr('data-height'))
                .attr('alt', '');

        img.load(function(){
            jQuery(this).appendTo( jQuery('.de-prop-gallery .main-photo') );
        });

        return false;
    });

    jQuery('.de-request-showing').click(function() {
        
        jQuery('#rep-window1').show();
        jQuery('#rep-window1 .wi-wrap-cross').click(function(){
            jQuery('#rep-window1').hide();
        });
        
        // Go to TOP
        return true;
    });

    jQuery('.auth-button').click(function() {
        
        jQuery('#rep-window2').show();
        jQuery('#rep-window2 .wi-wrap-cross').click(function(){
            jQuery('#rep-window2').hide();
        });

        // Go to TOP
        return true;
    });

    jQuery('#request-showing-form').submit(function(){

        var hasErrors = false;
        var form = jQuery(this);

        if( !isEmail(form.find('input[name="email"]').val()) ) {
            form.find('input[name="email"]').addClass('error');
            jQuery('#rep-window1').find('.error-message .email').show();
            hasErrors = true;
        } else {
            form.find('input[name="email"]').removeClass('error');
            jQuery('#rep-window1').find('.error-message .email').hide();
        }

        form.find('input.required, textarea.required').each(function(){

            if ( jQuery(this).val() == '' ) {
                hasErrors = true;
                jQuery(this).addClass('error');
            } else {
                jQuery(this).removeClass('error');
            }
        });

        if (hasErrors) {
            jQuery('#rep-window1').find('.error-message').show();
            return false;
        } else {
            jQuery('#rep-window1').find('.error-message').hide();
        }

        //form.find('.ground-form-message').fadeIn();

        return true;
    });

    jQuery('#de-registration-form').submit(function(){
        var hasErrors = false;
        var form = jQuery(this);

        if( !isEmail(jQuery(this).find('input[name="email"]').val()) ) {
            jQuery(this).find('input[name="email"]').parents('.wi-formline').addClass('error');

            alert('Invalid email address');
            return false;
        } else {
            jQuery(this).find('input[name="email"]').parents('.wi-formline').removeClass('error');
        }

        if( jQuery(this).find('input[name="email"]').val() != jQuery(this).find('input[name="email-2"]').val() ) {
            jQuery(this).find('input[name="email"]').parents('.wi-formline').addClass('error');
            jQuery(this).find('input[name="email-2"]').parents('.wi-formline').addClass('error');

            alert('Emails are not equal');
            return false;
        } else {
            jQuery(this).find('input[name="email"]').parents('.wi-formline').removeClass('error');
            jQuery(this).find('input[name="email-2"]').parents('.wi-formline').removeClass('error');
        }

        if( jQuery(this).find('input[name="password"]').val() != jQuery(this).find('input[name="password-2"]').val() ) {
            jQuery(this).find('input[name="password"]').parents('.wi-formline').addClass('error');
            jQuery(this).find('input[name="password-2"]').parents('.wi-formline').addClass('error');

            alert('Passwords are no equal');
            return false;
        } else {
            jQuery(this).find('input[name="password"]').parents('.wi-formline').removeClass('error');
            jQuery(this).find('input[name="password-2"]').parents('.wi-formline').removeClass('error');
        }

        form.find('input.required, select.required').each(function(){

            if ( jQuery(this).val() == '' ) {
                hasErrors = true;
                jQuery(this).parents('.wi-formline').addClass('error');
            } else {
                jQuery(this).parents('.wi-formline').removeClass('error');
            }

        });

        if (hasErrors) {
            return false;
        } else {
            jQuery('#rep-window2').find('.wi-received').show();
        }

        return true;
    });

    jQuery('#de-login-form').submit(function(){

        var hasErrors = false;
        var form = jQuery(this);

        if( !isEmail(jQuery(this).find('input[name="login-email"]').val()) ) {
            jQuery(this).find('input[name="login-email"]').parent('fieldset').addClass('error');

            alert('Invalid email address');
            return false;
        } else {
            jQuery(this).find('input[name="login-email"]').parent('fieldset').removeClass('error');
        }

        jQuery(this).find('input').each(function(){

            if ( jQuery(this).val() == '' ) {
                hasErrors = true;
                jQuery(this).parent('fieldset').addClass('error');
            } else {
                jQuery(this).parent('fieldset').removeClass('error');
            }

        });

        if (hasErrors) {
            return false;
        }

        return true;
    });

    jQuery('#order-form').find('select').change(function(){
        jQuery(this).parents('form').submit();
    });
    
/*
    jQuery('.ken-search form').submit(function(){
        jQuery(this).find('input').each(function(){
            if ( jQuery(this).val() == jQuery(this).attr('default') ) {
                jQuery(this).val('');
            }
        });

        return true;
    });

    jQuery('.ken-search form .clear-form').click(function(){

        jQuery('.ken-search form input[type="text"], .ken-search form input[type="hidden"]').each(function(){

            if ( jQuery(this).attr('default') ) {
                jQuery(this).val( jQuery(this).attr('default') );
                jQuery(this).removeClass( 'focus' );
            } else {
                jQuery(this).val( jQuery(this).attr('') );
            }

        });

        jQuery('.ken-search form input[type="checkbox"], .ken-search form input[type="hidden"]').each(function(){
            jQuery(this).prop('checked', false);
        });

        jQuery('.ken-search form select').each(function(){

            if ( jQuery(this).attr('default') ) {

                if ( jQuery(this).hasClass('wrg-multiple') ) {

                    var defaults = jQuery(this).attr('default');

                    jQuery(this).val( defaults.split(',') );
                }

            } else {
                jQuery(this).val('');
            }

        });

        // Clear map results
        if (typeof polygonPoints != 'undefined') {

            clearAllPolygonMarkers(false);
            clearSearchMarkers();
            jQuery('#search-property-list').html('');

        }

    });*/

});

function printPage() {
    setTimeout("window.print()", 500);
}

function isEmail(Mail) {
    Mail=Mail.toLowerCase();
    return (Mail.search(/^([a-z0-9\-\_\.\+]{1,100})\@([a-z0-9]+)([a-z0-9\-\.]*)([a-z0-9]+)\.([a-z]{2,6})$/) != -1);
}