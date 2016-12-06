/* ===================================================
 * ZL Framework
 * https://zoolanders.com/extensions/zl-framework
 * ===================================================
 * Copyright (C) JOOlanders SL
 * http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * ========================================================== */
!function(a){a.fn.equalChildHeights=function(){return a(this).each(function(){var b=0;a(this).children().css({"min-height":0}),a(this).children().each(function(){a(this).height()>b&&(b=a(this).height())}),a(this).children().css({"min-height":b})}),this},a.fn.equalChildOuterHeights=function(){return a(this).each(function(){a(this).children().css({"min-height":0});var b=0;a(this).children().each(function(){a(this).outerHeight()>b&&(b=a(this).outerHeight())}),a(this).children().each(function(){var c=a(this).outerHeight()-a(this).height();a(this).css({"min-height":b-c})})}),this},a.fn.matchHeightsAs=function(b){var c=b.height();return a(this).each(function(){a(this).children().css({"min-height":c})}),this},a.fn.equalWidths=function(){return a(this).each(function(){var b=0;a(this).children().each(function(){a(this).width()>b&&(b=a(this).width())}),a.browser.msie&&6==a.browser.version&&a(this).children().css({width:b}),a(this).children().css({"min-width":b})}),this}}(jQuery);