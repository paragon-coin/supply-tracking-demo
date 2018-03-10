(function () {

    /**
     * @var datamatrix array matrix of qr code values 1\0
     */
    window.getQRPrinter = function (datamatrix) {

        var printer = function (matrix) {

            var dataMatrix = [], dataMatrix_width = 0, dataMatrix_height = 0;
            var content = '';

            var setDataMatrix = function (matrix) {

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

                var wrapBefore = (typeof arguments[0] === 'undefined') ? '' : arguments[0];
                var wrapAfter = (typeof arguments[1] === 'undefined') ? '' : arguments[1];

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

                var dotSize = (typeof arguments[0] === 'undefined') ? 10 : arguments[0];

                var blackCSS = (typeof arguments[1] === 'undefined') ? '#000' : arguments[1];
                var whiteCSS = (typeof arguments[2] === 'undefined') ? '#fff' : arguments[2];
                // qr wrapping div block with relative positioning
                var str = '<div style="' +
                    'font-size:0;' +
                    'position:relative;' +
                    'width:' + (dotSize * dataMatrix_width) + 'px;' +
                    'height:' + (dotSize * dataMatrix_height) + 'px;' +
                    'background:' + whiteCSS + ' !important;' +
                    '-webkit-print-color-adjust: exact !important;' +
                    'color-adjust: exact !important;' +
                    '">';

                for (var row = 0; row < dataMatrix_height; row++) {

                    for (var col = 0; col < dataMatrix_width; col++) {
                        // qr point div block with absolute positioning
                        str += '<div style="' +
                            'position:absolute;' +
                            'top:' + (dotSize * row) + 'px;' +
                            'left:' + (dotSize * col) + 'px;' +
                            'height:' + dotSize + 'px;' +
                            'width:' + dotSize + 'px;';

                        if (dataMatrix[row][col] == 1) {
                            // 1 - colorized
                            str += 'background:' + blackCSS;
                        } else {
                            // 0 - white color
                            str += 'background:' + whiteCSS;
                        }

                        str += ' !important;' +
                            '-webkit-print-color-adjust: exact !important;' +
                            'color-adjust: exact !important;' +
                            '"></div>';

                    }

                }

                str += '</div>';
                content = str;

                return this;

            }

        };

        return new printer(datamatrix);

    };

}());
