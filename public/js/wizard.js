/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 44);
/******/ })
/************************************************************************/
/******/ ({

/***/ 44:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(45);


/***/ }),

/***/ 45:
/***/ (function(module, exports) {

var $validator; //
var messages = {};

$(function () {

    var rules = window.wizardGlovalRules = validation_rules;

    $(document).on('change, input, keyup, keypress, blur', '#wizardProfile .formGroup input', function () {
        $(this).closest('.formGroup').removeClass('hasError');
        $(this).closest('.formGroup').find('.errorText').remove();
    }).on('change', 'input[type="file"][name="docs[]"]', function () {

        if ($(this)[0].files.length > 0) {
            var file = $(this)[0].files[0];

            var sample = $(this).closest('[data-section="file-sample"]');
            sample.removeClass('hidden');

            sample.find('[data-extension]').text(file.name.substr(file.name.lastIndexOf('.') + 1).toUpperCase());
            sample.find('[data-size]').text(helper.files.convert_size(file.size));
            sample.find('[data-file-name]').val(file.name.substr(0, file.name.lastIndexOf('.')));
        }
    }).on('click', '.addFileSection', function () {
        var sample = $('.fileCard.hidden[data-section="file-sample"]').clone();

        $('.fileCardHolder').append(sample);

        sample.find('input[type="file"]').click();
    })

    // .on('focus','.formGroup.hasError',function () {
    //     $(this).closest('.formGroup').removeClass('hasError');
    // })

    .on('click', '.detachFile', function () {
        $(this).closest('[data-section="file-sample"]').remove();
    }).on('click', '.markForDeletion', function () {

        var val = 'y' == $(this).attr('data-value') ? 'n' : 'y';

        $(this).val(val);

        if ('y' == val) {

            $(this).closest('[data-section="existing-file"]').fadeTo("slow", 0.33);
        } else {

            $(this).closest('[data-section="existing-file"]').fadeTo("fast", 1.00);
        }

        $(this).closest('[data-section="existing-file"]').find('[name$="][delete]"][name^="existingFile["]').val(val);
    }).on('change', '#wizardProfile [name="harvest[weight_measurement]"]', function () {
        var sVal = $(this).val();
        if (sVal.length > 0) {
            $('.inputCaption').each(function () {
                $(this).html(sVal);
            });
        }
    }).on('click', '#wizardProfile .addRecord', function () {
        var prop = $('#wizardProfile [data-section="prop-sample"].hidden').eq(0).clone();
        prop.find('input').val('');
        prop.removeClass('hidden');
        $('#wizardProfile [data-section="props"]').append(prop);
    }).on('click', '#wizardProfile .removeRecord', function () {
        $(this).closest('[data-section="prop-sample"]').remove();
    });

    $validator = $('#wizardProfile form').validate({
        rules: rules,
        messages: messages,
        errorPlacement: function errorPlacement(error, element) {

            // remove prev errors
            $(element).closest('.formGroup').find('.inputHolder .errorText').remove();
            $(element).closest('.formGroup').addClass('hasError');
            $(element).closest('.formGroup .inputHolder').append('<div class="errorText"><span class="error">' + error[0].innerHTML + '</span></div>');
        }
    });

    // Wizard Initialization
    $('#wizardProfile').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        'nextSelector': '.btn-next',
        'previousSelector': '.btn-previous',

        onNext: function onNext(tab, navigation, index) {

            var $valid = $('#wizardProfile form').valid();
            console.log('validation', $valid, rules);
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
        },

        onTabClick: function onTabClick(tab, navigation, index) {

            var $valid = $('#wizardProfile form').valid();

            if (!$valid) {
                return false;
            } else {
                return true;
            }
        },

        onTabShow: function onTabShow(tab, navigation, index) {
            $('.stepsNameHolder .active').removeClass('active');
            $(tab).addClass('active');

            var $total = navigation.find('li').length;
            var $current = index + 1;

            var $wizard = navigation.closest('#wizardProfile');

            // If it's the last tab then hide the last button and show the finish instead
            if ($current >= $total) {
                $($wizard).find('.btn-next').hide();
                $($wizard).find('.btn-finish').show();
            } else {
                $($wizard).find('.btn-next').show();
                $($wizard).find('.btn-finish').hide();
            }

            if ($current > 1) {
                $($wizard).find('.btn-previous').show();
            } else {
                $($wizard).find('.btn-previous').hide();
            }

            button_text = navigation.find('li:nth-child(' + $current + ') a').html();

            setTimeout(function () {
                $('.moving-tab').text(button_text);
            }, 150);

            var checkbox = $('.footer-checkbox');

            if (!index == 0) {
                $(checkbox).css({
                    'opacity': '0',
                    'visibility': 'hidden',
                    'position': 'absolute'
                });
            } else {
                $(checkbox).css({
                    'opacity': '1',
                    'visibility': 'visible'
                });
            }
        }
    });

    $('#wizardProfile .btn-finish').click(function (e) {
        if ($('#wizardProfile form').valid()) {
            $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Finish');
            $(this).closest('form').submit();
        }
    });
});

/***/ })

/******/ });