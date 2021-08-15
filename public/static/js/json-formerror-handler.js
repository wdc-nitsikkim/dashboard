const jsonFormErrorHandler = (function ($) {
    'use strict';

    function removeInvalidClasses(container) {
        container.find('input').each(function () {
            $(this).removeClass('is-invalid');
        });
    }

    function addInvalidClasses(elements, errors) {
        errors.forEach((val, index) => {
            const row = elements.get(index);

            for (const key in val) {
                const tmpKey = key + '[]';
                /* failsafe, incase a row is deleted before server response */
                try {
                    $(row).find(`input[name="${tmpKey}"]`).addClass('is-invalid');
                } catch (err) {
                    continue;
                }
            }
        });
    }

    function reFormatErrors(errors) {
        let arr = [];
        for (const key in errors) {
            let newKey = key.split('.');
            /* last element contains the index */
            const arrKey = Number(newKey.pop());
            if (typeof arr[arrKey] == 'undefined') {
                arr[arrKey] = {};
            }
            /* only the first error is retrieved, so [0] */
            arr[arrKey][newKey] = errors[key][0];
        }
        return arr;
    }

    return {
        reformat: reFormatErrors,
        addClass: addInvalidClasses,
        removeClass: removeInvalidClasses
    }
})(jQuery);
