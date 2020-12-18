/*global $, document, confirm*/

$(document).ready(function () {
    
    'use strict';
    
    // Dashboard    [ Span In Div panel-heading ]
    
    $('.toggle-info').click(function () {
       
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
        
        if ($(this).hasClass('selected')) {
            
            $(this).html('<i class="fas fa-minus"></i>');
            
        } else {
            
            $(this).html('<i class="fas fa-plus"></i>');
            
        }
        
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
    
    
    // Convert Password Field To Text Field On Hover
    
    var passField = $('.password'); // .password is class in password field in insert form
    
    $('.show-pass').hover(function () {
        
        passField.attr('type', 'text');
        
    }, function () {
        
        passField.attr('type', 'password');
        
    });
    
    
    // Confirmation Message On Delete Button In Delete Page
    
    $('.confirm').click(function () {
        
        return confirm('Are You Sure?');
        
    });
    
    // Category View Option
    
    $('.cat h3').click(function () {
       
        $(this).next('.full-view').fadeToggle(500);
        
    });
    
    $('.option span').click(function () {
        
        $(this).addClass('active').siblings('span').removeClass('active');
        
        if ($(this).data('view') === 'full') {
            
            
            $('.cat .full-view').fadeIn(200);
            
        } else {
            
            $('.cat .full-view').fadeOut(200);
        }
        
    });

    // Trigger Or Fire The selectBoxIt In Item.php Page
    
    $("select").selectBoxIt({

        autoWidth: false

    });


    //  Show Delete Button In categories.php

    $('.edit-link').hover(function () {

        $(this).find('.show-delete').fadeIn();

    }, function () {

        $(this).find('.show-delete').fadeOut();

    });

});
