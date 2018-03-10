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
/******/ 	return __webpack_require__(__webpack_require__.s = 46);
/******/ })
/************************************************************************/
/******/ ({

/***/ 46:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(47);


/***/ }),

/***/ 47:
/***/ (function(module, exports) {

(function () {

    /**
     * @var datamatrix array matrix of qr code values 1\0
     */
    window.getQRPrinter = function (datamatrix) {

        var printer = function printer(matrix) {

            var dataMatrix = [],
                dataMatrix_width = 0,
                dataMatrix_height = 0;
            var content = '';

            var setDataMatrix = function setDataMatrix(matrix) {

                dataMatrix = matrix;

                if (matrix.length && dataMatrix[0].length) {

                    dataMatrix_height = dataMatrix.length;
                    dataMatrix_width = dataMatrix[0].length;
                } else {

                    throw 'Seems like qr matrix is corupted';
                }
            };

            setDataMatrix(matrix);

            /**
             * Function to open printing dialog at client side
              */
            this.print = function () {

                var wrapBefore = typeof arguments[0] === 'undefined' ? '' : arguments[0];
                var wrapAfter = typeof arguments[1] === 'undefined' ? '' : arguments[1];

                popup = window.open();
                popup.document.write(wrapBefore + content + wrapAfter);
                popup.focus(); //required for IE
                popup.print();
                setTimeout(function () {
                    // attach close() method, after print dialog is opened;
                    popup.close();
                }, 50);
            };

            /**
             * Function is for generating qr code with important css rules with "!important" options
             * reason : https://github.com/milon/barcode do not generates rules for printing DIV background color
             * @returns {printer}
             */
            this.generate = function () {

                var dotSize = typeof arguments[0] === 'undefined' ? 10 : arguments[0];

                var blackCSS = typeof arguments[1] === 'undefined' ? '#000' : arguments[1];
                var whiteCSS = typeof arguments[2] === 'undefined' ? '#fff' : arguments[2];
                // qr wrapping div block with relative positioning
                var str = '<div style="' + 'font-size:0;' + 'position:relative;' + 'width:' + dotSize * dataMatrix_width + 'px;' + 'height:' + dotSize * dataMatrix_height + 'px;' + 'background:' + whiteCSS + ' !important;' + '-webkit-print-color-adjust: exact !important;' + 'color-adjust: exact !important;' + '">';

                for (var row = 0; row < dataMatrix_height; row++) {

                    for (var col = 0; col < dataMatrix_width; col++) {
                        // qr point div block with absolute positioning
                        str += '<div style="' + 'position:absolute;' + 'top:' + dotSize * row + 'px;' + 'left:' + dotSize * col + 'px;' + 'height:' + dotSize + 'px;' + 'width:' + dotSize + 'px;';

                        if (dataMatrix[row][col] == 1) {
                            // 1 - colorized
                            str += 'background:' + blackCSS;
                        } else {
                            // 0 - white color
                            str += 'background:' + whiteCSS;
                        }

                        str += ' !important;' + '-webkit-print-color-adjust: exact !important;' + 'color-adjust: exact !important;' + '"></div>';
                    }
                }

                str += '</div>';
                content = str;

                return this;
            };
        };

        return new printer(datamatrix);
    };
})();

/***/ })

/******/ });