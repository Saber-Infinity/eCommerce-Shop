/*global $, document, confirm*/

$(document).ready(function () {
    
    'use strict';

    // Switch Between Login & Signup

    $('.login-page h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(100);

    });
    
    // Hide Placeholder On Form Focus
    
    $('[placeholder]').focus(function () {
            
        $(this).attr('data-text', $(this).attr('placeholder'));
        
        $(this).attr('placeholder', '');
        
    }).blur(function () {
        
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    
    
    // Add Asterisk On Required Fields
    
    $('input').each(function () {
       
        if ($(this).attr('required')) {
            
            $(this).after('<span class="asterisk"> * </span>');
        }
        
    });
    
    
    // Confirmation Message On Delete Button In Delete Page
    
    $('.confirm').click(function () {
        
        return confirm('Are You Sure?');
        
    });
    

    // Trigger Or Fire The selectBoxIt In Item.php Page
    
    $("select").selectBoxIt({

        autoWidth: false

    });
    

    //  [newads.php File > Form > input[name] Has Class live-name]

    $('.live').keyup(function () {

        //console.log($(this).data('class')); If I write In Input Field Immediately Write In Console

        $( $(this).data('class') ).text($(this).val());

    });

    // Test Read More...
    
    $('.item-box .caption p .show-hide').click(function () {

        $(this).prev().fadeIn(0, function () {

            $(this).hide();

        });

    });

});