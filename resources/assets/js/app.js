
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
    window.toastr = require('toastr');
    require('bootstrap');
    require('twitter-bootstrap-wizard');
    require('sweetalert');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

//mobile menu
$(document).ready(function(){
    $('#menuTrigger').on('click', function(e) {
        var menu = $('.menuWrapper');
        var body = $('body');

        e.preventDefault();
        body.addClass('overflowHidden');
        menu.addClass('in');

    });

    $('#closeMenu').on('click', function(e) {
        var menu = $('.menuWrapper');
        var body = $('body');

        e.preventDefault();
        body.removeClass('overflowHidden');
        menu.removeClass('in');

    })
});
//end


//Aside menu
$(document).ready(function() {
    var trigger = $('#asideMenuTrigger');
    var menu = $('.asideMenu');
    var layer = $('.pageLayerHolder');

    function toggleMenu () {
        menu.toggleClass('out');
        layer.toggleClass('asideOut');
    }

    trigger.on('click', function(e) {
        e.preventDefault();
        toggleMenu();
    });

});
//End
$(document).ready(function() {
    $('#wizardProfile form a').on('click', function(e){
        e.preventDefault();
    });

    helper = {

        js:{

            object:{

                sortByKey: function(obj){

                    var  result = {};

                    var keys = Object.keys(obj),
                        i, len = keys.length;

                    keys.sort();

                    for (i = 0; i < len; i++) {
                        k = keys[i];

                        if(obj[k] !== null && (typeof obj[k] == typeof {} || typeof obj[k] == typeof []) ){

                            result[k] = helper.js.object.sortByKey(obj[k]);

                        }else{

                            result[k] = obj[keys[i]];

                        }

                    }

                    return result;

                },

                eachPropertyAsString: function(obj){

                    var  result = {};

                    var keys = Object.keys(obj),
                        i, len = keys.length;

                    keys.sort();

                    for (i = 0; i < len; i++) {
                        k = keys[i];

                        if(obj[k] !== null && (typeof obj[k] == typeof {} || typeof obj[k] == typeof []) ){

                            result[k] = helper.js.object.eachPropertyAsString(obj[k]);

                        }else{

                            result[k] = obj[keys[i]] + '';

                        }

                    }

                    return result;

                }

            }

        },

        files: {
            convert_size: function (bytes) {
                /**
                 * @see https://stackoverflow.com/a/14919494/3894584
                 */
                var si = (typeof arguments[1] !== 'undefined') ? arguments[1] : true;
                var thresh = si ? 1000 : 1024;
                if(Math.abs(bytes) < thresh) {
                    return bytes + ' B';
                }
                var units = si
                    ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
                    : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
                var u = -1;
                do {
                    bytes /= thresh;
                    ++u;
                } while(Math.abs(bytes) >= thresh && u < units.length - 1);
                return bytes.toFixed(1)+' '+units[u];
                /*
                 humanFileSize(5000,true)            > "5.0 kB"
                 humanFileSize(5000,false)           > "4.9 KiB"
                 */
            }
        }
    }
});

//Floating labels

$(document).ready(function() {
    $(document)
        .on('focusout', '.floatLabel input', function(){
            $('.floatLabel').removeClass('isFocused');
        })

        .on('focus', '.floatLabel input', function(){
            $(this).closest('.floatLabel').addClass('isFocused');
        })

        .on('keyup', '.floatLabel input', function(){
            if($(this).val().length > 0){
                $(this).closest('.floatLabel').addClass('isFilled');
            }

            else{
                $(this).closest('.floatLabel').removeClass('isFilled');
            }
        });

    var values = {};
    var validate = $('.floatLabel input').each(function() {
        if($(this).val().length > 0){
            $(this).closest('.floatLabel').addClass('isFilled');
        }
        else{
            $(this).closest('.floatLabel').removeClass('isFilled');
        }
    });

    if($('.floatLabel input').hasClass('location-address')){
        $('input').attr('placeholder','');
    }

});

//End