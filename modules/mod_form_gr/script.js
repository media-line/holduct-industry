jQuery(document).ready(function() {

    function setCaptchaCookie () {
        var ids = new Array();

        jQuery('.mod_form_gr').find('form').each(function () {
            ids.push(jQuery(this).attr('data-captcha'));
        });

        var idsJSON = JSON.stringify(ids);

        var siteKey = jQuery('.siteKey').val();

        var date = new Date(new Date().getTime() + 60 * 60 * 1000);
        document.cookie = "siteKey="+ siteKey +"; expires=" + date.toUTCString();

        document.cookie = "idsJSON="+ idsJSON +"; expires=" + date.toUTCString();
    }

    function formGrSend (json) {
        var id = "form_back_"+json.id;
        var form = document.getElementById(id);
        var formData = new FormData(form);

        var error = 0;

        for (var i=0; i < json.fields.length; i++) {
            if (json.fields[i]['title']) {
                formData.append("namefield"+i, json.fields[i]['title']);
            } else if (json.fields[i]['placeholder']) {
                formData.append("namefield"+i, json.fields[i]['placeholder']);
            }

            var field = jQuery('#'+id).find('[name=field'+i+']').val();
            
            var currentField = jQuery('#'+id).find('[name=field'+i+']');
            
            if (json.fields[i]['required']) {
                
                if (field == '') {
                    currentField.addClass('border_red');
                    currentField.closest('.field').addClass('is-empty');
                    error = 1;
                }  else {
                    currentField.removeClass('border_red');
                    currentField.closest('.field').removeClass('is-empty');
                }
            } 
            if ((json.fields[i]['type'] == 'email') && (field != '')) {
                if (isValidEmailAddress(currentField.val())) {
                    currentField.removeClass('border_red');
                    currentField.closest('.field').removeClass('no-valid');
                } else {
                    currentField.addClass('border_red');
                    currentField.closest('.field').addClass('no-valid');
                    error = 1;
                }
            } 
            if ((json.fields[i]['type'] == 'numeric') && (field != '')) {
                if (isValidNumber(currentField.val())) {
                    currentField.removeClass('border_red');
                    currentField.closest('.field').removeClass('no-valid');
                } else {
                    currentField.addClass('border_red');
                    currentField.closest('.field').addClass('no-valid');
                    error = 1;
                }
            } 
            
            if((json.fields[i]['type'] == 'phone') && (field != '')) {
                var currentField = jQuery('#'+id).find('[name=field'+i+']');
                
                if (isValidPhone(currentField.val())) {
                    currentField.removeClass('border_red');
                    currentField.closest('.field').removeClass('no-valid');
                } else {
                    currentField.addClass('border_red');
                    currentField.closest('.field').addClass('no-valid');
                    error = 1;
                }
            }
        }       

        formData.append("mailSubject", json.mailHead);
        formData.append("recipient", json.recipient);
        formData.append("quantityFields", json.quantityFields);
        formData.append("captchaSecretKey", json.captchaSecretKey);
        
        if ((jQuery("input").is("#form_page_link")) && (jQuery("#form_page_link").val() != '')){
            formData.append("pageLinkFieldName", json.pageLinkFieldName);
            formData.append("pageLink", jQuery("#form_page_link").val());
            formData.append("imageIdFieldName", json.imageIdFieldName);
            formData.append("imageId", jQuery("#form_image_id").val());
        }

        if (json.captchaOn) {
            formData.append("captcha", true);

            if (!grecaptcha.getResponse(captcha[json.id])) {
                error = 1;
            }
        }

        var capfield = jQuery('[name=capfield]', this.form).val();
        if (capfield != '') {
            error = 1;
        }
        
        if (!error) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/modules/mod_form_gr/mailer.php",true);
            xhr.onreadystatechange = function() {
                if(xhr.responseText == 'success'){
                    //alert('asd');
                    //UIkit.modal('.uk-modal').hide();
                    UIkit.modal('#answer'+json.id).show();
                }
            }
            xhr.send(formData);
        }

    }

    jQuery('.js-form-send').click(function(e) {
        e.preventDefault();

        var form = jQuery(this).closest('form').attr("id");

        var json = jQuery.parseJSON(jQuery('.'+form).find('.js-form-gr-json').val());

        formGrSend(json);
    });
    
    function loadFileHandler () {
        jQuery('.file-block').on('change', 'input[type="file"]', function() {
            jQuery(this).closest('.file-block').find('.js-filename').append("<div class='file-control'>"+jQuery(this).val().replace('C:\\fakepath', '...')+" <em>("+jQuery(this)[0].files[0].size+" kb)</em><i class='icon-cross-out js-delete-file'></i></div>");         
            	
            var fileInputCopy = jQuery(this).closest('.file-block').find('label').eq(0).clone();
            
            jQuery(this).closest('.file-block').find('.files').append(fileInputCopy);
        });
        
        jQuery('.file-block').on('click', '.js-delete-file', function() {
            var indexFile = jQuery(this).closest('.file-control').index(); 
            
            jQuery(this).closest('.file-block').find('.files label').eq(indexFile).remove();   

            jQuery(this).closest('.file-control').remove();
        });
    }

    setCaptchaCookie ();
    loadFileHandler();
});

function captchaCallBack () {
    var siteKey = getCookie('siteKey');
    var idsJSON = JSON.parse(getCookie('idsJSON'));

    captcha = new Array();
    jQuery.each(idsJSON, function (index, value) {
        captcha[value] = grecaptcha.render('g-recaptcha'+value, {
            'sitekey' : siteKey, //Replace this with your Site key
            'theme' : 'light'
        });
    });
}


function getCookie (name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
function isValidPhone(phone) {
    var pattern = new RegExp(/^[+\d][\d\(\)\ -]{4,20}$/);
    return pattern.test(phone);
}
function isValidNumber(phone) {
    var pattern = new RegExp(/^[\d]{1,50}$/);
    return pattern.test(phone);
}