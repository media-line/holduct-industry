/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

jQuery(function($) {

    var config = $('html').data('config') || {};

    // убираем копирайт faboba
    jQuery("a[href^='http://www.faboba.com']").css({'display': 'none'});
    jQuery("a[href^='http://faboba.com']").css({'display': 'none'});

    // обработка кликов на карте на главной
    jQuery('.uk-staticmap-marker').click(function(){
        jQuery('.uk-staticmap-marker-caption').addClass('uk-hidden');
        jQuery(this).find('.uk-staticmap-marker-caption').removeClass('uk-hidden');
    });

    //обработка кнопки назад
    jQuery('.js-back-button').click(function(event){
        //event.preventDefault();
        window.history.back();
        return false;
    });
});
