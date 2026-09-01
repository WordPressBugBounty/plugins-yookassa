(function () {
    'use strict';

    var FIELD_ID = 'wc-yookassa_sber_bnpl-phone';

    function formatPhone(raw) {
        var digits = String(raw).replace(/\D+/g, '');

        if (digits.charAt(0) === '8') {
            digits = '7' + digits.slice(1);
        } else if (digits && digits.charAt(0) !== '7') {
            digits = '7' + digits;
        }

        digits = digits.slice(0, 11);

        if (digits.length === 0) {
            return '';
        }

        var result = '+7';

        if (digits.length > 1) {
            result += ' (' + digits.slice(1, 4);
        }
        if (digits.length >= 4) {
            result += ') ' + digits.slice(4, 7);
        }
        if (digits.length >= 7) {
            result += '-' + digits.slice(7, 9);
        }
        if (digits.length >= 9) {
            result += '-' + digits.slice(9, 11);
        }

        return result;
    }

    function applyMask(input) {
        var start = input.selectionStart || 0;
        var digitsBeforeCaret = (input.value.slice(0, start).match(/\d/g) || []).length;
        var formatted = formatPhone(input.value);

        if (input.value !== formatted) {
            input.value = formatted;
        }

        var pos = 0;
        var digitCount = 0;
        while (pos < formatted.length && digitCount < digitsBeforeCaret) {
            if (/\d/.test(formatted.charAt(pos))) {
                digitCount++;
            }
            pos++;
        }
        input.setSelectionRange(pos, pos);
    }

    function initField() {
        var input = document.getElementById(FIELD_ID);
        if (input) {
            applyMask(input);
        }
    }

    document.addEventListener('input', function (event) {
        var target = event.target;
        if (target && target.id === FIELD_ID && target.type === 'tel') {
            applyMask(target);
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initField);
    } else {
        initField();
    }
})();