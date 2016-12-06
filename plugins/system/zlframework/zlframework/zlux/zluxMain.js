/* ===================================================
 * ZL Framework
 * https://zoolanders.com/extensions/zl-framework
 * ===================================================
 * Copyright (C) JOOlanders SL
 * http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * ========================================================== */
!function(a,b,c,d){"use strict";var e=a.zlux||{};e.fn||(e.version="1.0",e.zoo={},e.url={},e.url._root="",e.url.root=function(a){var b=new RegExp("^"+e.url._root_path);return a="string"==e.utils.toType(a)?a.replace(b,""):"",e.url._root+a},e.url._base="",e.url.base=function(a){return e.url._base+(a?a:"")},e.url._zlfw="plugins/system/zlframework/zlframework/",e.url.zlfw=function(a){return e.url._zlfw+(a?a:"")},e.url.ajax=function(b,c,f){var f=f==d?{}:f,g=f.app_id?f.app_id:e.zoo.app_id,h=f.option?f.option:e.com_zl?"com_zoolanders":"com_zoo";delete f.option,delete f.app_id;var i=e.url._base+"index.php?option="+h+"&controller="+b+"&task="+c+(g?"&app_id="+g:"")+"&format=raw"+(a.isEmptyObject(f)?"":"&"+a.param(f));return i},e.fn=function(b,c){var f=arguments,g=b.match(/^([a-z\-]+)(?:\.([a-z]+))?/i),h=g[1],i=g[2];return e[h]||e.utils[h]?this.each(function(){if(e.utils[h])e.utils[h](this,i,Array.prototype.slice.call(f,1));else{var b=a(this),g=b.data(h);g||b.data(h,g=new e[h](this,i?d:c)),i&&g[i].apply(g,Array.prototype.slice.call(f,1))}}):(a.error("ZLUX plugin ["+h+"] does not exist."),this)},e.lang={},e.lang.strings={},e.lang.set=function(b){a.extend(e.lang.strings,b)},e.lang.get=function(a){return e.lang.strings[a]||a},e.assets={},e.assets._ress={},e.assets.known={dates:{css:"plugins/system/zlframework/zlframework/zlux/DatesManager/style.css",js:"plugins/system/zlframework/zlframework/zlux/DatesManager/plugin.js"},dialog:{css:"plugins/system/zlframework/zlframework/zlux/DialogManager/style.css",js:"plugins/system/zlframework/zlframework/zlux/DialogManager/plugin.js"},fields:{css:"plugins/system/zlframework/zlframework/zlux/FieldsManager/style.css",js:"plugins/system/zlframework/zlframework/zlux/FieldsManager/plugin.js"},files:{css:"plugins/system/zlframework/zlframework/zlux/FilesManager/style.css",js:"plugins/system/zlframework/zlframework/zlux/FilesManager/plugin.js"},items:{css:"plugins/system/zlframework/zlframework/zlux/ItemsManager/style.css",js:"plugins/system/zlframework/zlframework/zlux/ItemsManager/plugin.js"}},e.assets.load=function(b,c,f){var g=[];"string"==e.utils.toType(b)&&a.zlux.assets.known[b]!=d&&(b=a.map(e.assets.known[b],function(a){return[a]})),b=a.isArray(b)?b:[b];for(var h=0,i=b.length;i>h;h++)b[h]&&(e.assets._ress[b[h]]||(e.assets._ress[b[h]]=b[h].match(/\.js$/)?e.assets.getScript(e.url.root(b[h])):e.assets.getCss(e.url.root(b[h]))),g.push(e.assets._ress[b[h]]));return a.when.apply(a,g).done(c).fail(function(){f?f():a.error("Require failed: \n"+b.join(",\n"))})},e.assets.getScript=function(b,d){var e=a.Deferred(),f=c.createElement("script");return f.async=!0,f.onload=function(){e.resolve(),d&&d(f)},f.onerror=function(){e.reject(b)},f.onreadystatechange=function(){("loaded"==this.readyState||"complete"==this.readyState)&&(e.resolve(),d&&d(f))},f.src=b,c.getElementsByTagName("head")[0].appendChild(f),e.promise()},e.assets.getCss=function(b,d){var e=a.Deferred(),f=c.createElement("link");f.type="text/css",f.rel="stylesheet",f.href=b,c.getElementsByTagName("head")[0].appendChild(f);var g=c.createElement("img");return g.onerror=function(){e.resolve(),d&&d(f)},g.src=b,e.promise()},e.utils={},e.utils.toType=function(a){return{}.toString.call(a).match(/\s([a-z|A-Z]+)/)[1].toLowerCase()},e.utils.options=function(b){if(a.isPlainObject(b))return b;var c=b?b.indexOf("{"):-1,d={};if(-1!=c)try{d=new Function("","var json = "+b.substr(c)+"; return JSON.parse(JSON.stringify(json));")()}catch(e){}return d},e.support={},e.support.touch="ontouchstart"in b&&navigator.userAgent.toLowerCase().match(/mobile|tablet/)||b.DocumentTouch&&c instanceof b.DocumentTouch||b.navigator.msPointerEnabled&&b.navigator.msMaxTouchPoints>0||b.navigator.pointerEnabled&&b.navigator.maxTouchPoints>0||!1,e.ajax={},e.ajax.request=function(b){return a.Deferred(function(c){var d={success:!1,errors:[]};a.ajax(b).done(function(b){try{var b=a.parseJSON(b);b.success?c.resolve(b):c.reject(b)}catch(e){d.errors.push(String(b)),d.errors.push("An server-side error occurred. "+String(e)),c.reject(d)}}).fail(function(a,b,e){switch(a.status){case 403:d.errors.push("The session has expired.");break;case 404:d.errors.push("The requested URL is not accesible.");break;case 500:d.errors.push("A server-side error has occurred.");break;default:d.errors.push("An error occurred: "+b+"nError: "+e)}d.status=a.status,c.reject(d)})}).promise()},a.zlux=e,a.fn.zlux=e.fn)}(jQuery,window,document),function(a,b,c,d){"use strict";var e=function(){};a.extend(e.prototype,{name:"Main",options:{},events:{},trigger:function(a){var b,c,d=this.events[a.toLowerCase()];if(c=Array.prototype.slice.call(arguments),c[0]=this,d)for(b=0;b<d.length;b++)if(d[b].func.apply(d[b].scope,c)===!1)return!1;return this.element&&this.element.trigger("zlux."+a,c),!0},bind:function(a,b,c){var d=this;return a.split(" ").each(function(a){var e;a=a.toLowerCase(),e=d.events[a]||[],e.push({func:b,scope:c||d}),d.events[a]=e}),this},unbind:function(a){a=a.toLowerCase();var b,c=this.events[a],e=arguments[1];if(c){if(e!==d){for(b=c.length-1;b>=0;b--)if(c[b].func===e){c.splice(b,1);break}}else c=[];c.length||delete this.events[a]}},unbindAll:function(){var b=this;a.each(b.events,function(a,c){b.unbind(c)})},hasEventListener:function(a){return!!this.events[a.toLowerCase()]},_ErrorLog:function(a,c){var e=this,f=e.ID===d?e.name+": "+c:e.name+" warning (id = '"+e.ID+"'): "+c;return 0===a?void alert(f):void(b.console&&console.log&&console.log(f))},_:function(b){return a.zlux.lang.get(b)},sprintf:function(a){var b=[].slice.call(arguments,1),c="";return a.split(/%[sdf]/).forEach(function(a){c+=a,b.length&&(c+=b.shift())}),c},cleanPath:function(a){return a?a.replace(/\\/g,"/").replace(/\/\//g,"/").replace(/undefined/g,"").replace(/\/$/g,"").replace(/:\//g,"://"):void 0}}),a.zlux[e.prototype.name]=e}(jQuery,window,document),function(a){"use strict";var b=function(){};a.extend(b.prototype,a.zlux.Main.prototype,{name:"Manager",reload:function(){var a=this,b=a.oTable.fnSettings();b.bReloading=!0,a.oTable.fnReloadAjax(b.sAjaxSource)},pushMessageToObject:function(b,c){"string"==typeof c?c=a("<div>"+c+"</div>"):c.length>1&&a.each(c,function(b,d){c[b]=a("<div>"+d+"</div>")});var d=a(".zlux-x-msg",b.dom),e=a('<div class="zlux-x-details-content zlux-x-msg" />').hide().append(c,a('<i class="zlux-x-msg-remove icon-remove" />').on("click",function(){e.fadeOut()})).prependTo(a(".zlux-x-details",b.dom)).slideDown("fast",function(){d.fadeOut("slow",function(){a(this).remove()})});return e}}),a.zlux[b.prototype.name]=b}(jQuery,window,document),function(a){"use strict";var b=function(c,d,e){var f=a(c);d=null==d?"on":d,b.prototype[d](f,e)};a.extend(b.prototype,a.zlux.Main.prototype,{name:"spin",on:function(b,c){var d=this;d.icon_class=!1;var e=c[0]?c[0]:{},f={"class":e["class"]?e["class"]:"",affix:e.affix?e.affix:"append"};a("i",b)[0]?(d.icon_class=a("i",b).attr("class"),a("i",b).attr("class","uk-icon-spinner uk-icon-spin")):"replace"==f.affix?b.html(a('<i class="uk-icon-spinner uk-icon-spin"></i>').addClass(f["class"])):b[f.affix](a('<i class="uk-icon-spinner uk-icon-spin"></i>').addClass(f["class"]))},off:function(b){var c=this;a("i",b).removeClass("uk-icon-spinner uk-icon-spin"),c.icon_class&&a("i",b).attr("class",c.icon_class)}}),a.zlux.utils[b.prototype.name]=b}(jQuery,window,document),function(a){"use strict";var b=function(c,d,e){var f=a(c);d=null==d?"initialize":d,b.prototype[d](f,e)};a.extend(b.prototype,a.zlux.Main.prototype,{name:"animate",initialize:function(a,b){var c=a,d=b[0].split(" "),e=b[1]?b[1]:null;d="uk-animation-"+(1==d.length?d:d.join(" uk-animation-")),c.addClass(d).one("webkitAnimationEnd oanimationend msAnimationEnd animationend",function(){c.removeClass(d),e&&e.apply(c)})}}),a.zlux.utils[b.prototype.name]=b}(jQuery,window,document),function(a){"use strict";var b=function(){};a.extend(b.prototype,{name:"zluxSaveElement",options:{msgSaveElement:"Save Element",item_id:0,elm_id:""},initialize:function(b,c){this.options=a.extend({},this.options,c);var d=this;a('<a class="btn btn-small save" href="javascript:void(0);" />').append('<i class="icon-ok-sign"></i> '+d.options.msgSaveElement).on("click",function(){var b=a(this).addClass("btn-working"),c=b.closest(".element").find("input, textarea").serializeArray();a.post(d.AjaxUrl+"&task=saveelement&item_id="+d.options.item_id+"&elm_id="+d.options.elm_id,c,function(){b.removeClass("btn-working")})}).appendTo(b.find(".btn-toolbar"))}}),a.fn[b.prototype.name]=function(){var c=arguments,d=c[0]?c[0]:null;return this.each(function(){var e=a(this);if(b.prototype[d]&&e.data(b.prototype.name)&&"initialize"!==d)e.data(b.prototype.name)[d].apply(e.data(b.prototype.name),Array.prototype.slice.call(c,1));else if(!d||a.isPlainObject(d)){var f=new b;b.prototype.initialize&&f.initialize.apply(f,a.merge([e],c)),e.data(b.prototype.name,f)}else a.error("Method "+d+" does not exist on jQuery."+b.name)})}}(jQuery,window,document),function(a){"use strict";var b=function(c,d){var e=this,f=a(c);f.data(b.prototype.name)||(e.element=a(c),e.options=a.extend({},b.prototype.options,d),this.events={},e.initialize(),e.element.data(b.prototype.name,e))};a.extend(b.prototype,a.zlux.Main.prototype,{name:"Example",options:{},initialize:function(){}}),a.zlux[b.prototype.name]=b}(jQuery,window,document),function(a){"use strict";var b=function(b){this.options=a.extend({},this.options,b),this.events={},this.initialize()};a.extend(b.prototype,a.zlux.Main.prototype,{name:"Example",options:{},initialize:function(){}}),a.zlux[b.prototype.name]=function(){var a=arguments;return new b(a[0]?a[0]:{})}}(jQuery,window,document),function(a,b,c){"use strict";var d=function(b,c){var e=this,f=a(b);f.data(d.prototype.name)||(e.element=a(b),e.options=a.extend({},d.prototype.options,c),this.events={},e.initialize(),e.element.data(d.prototype.name,e))};a.extend(d.prototype,a.zlux.Main.prototype,{name:"Example",options:{},initialize:function(){}});var e=a.zlux.support.touch?"click":"mouseenter";a(c).on(e+".namespace.othernamespace","[data-example-dom]",function(b){var c=a(this);if(!c.data("Example")){{new d(c,a.zlux.utils.options(c.data("example-dom")))}b.preventDefault()}})}(jQuery,window,document);