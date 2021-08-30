const find = (function ($) {
    'use strict';

    function resetSearchElements(elements) {
        return new Promise(resolve => {
            elements.removeClass('d-none');
            resolve(true);
        });
    }

    /**
     * Performs a case-insensitive search among the provided elements
     *
     * @param {jQuery<HTMLCollection>} elements
     * @param {string} searchString
     * @param {string|null} additionalAttr
     * @returns {Promise}
     */
    function searchInElements(elements, searchString, additionalAttr = null) {
        searchString = searchString.toLowerCase();

        return new Promise(resolve => {
            elements.each(function () {
                const element = $(this);
                const text = element.text().toLowerCase();
                const attrText = additionalAttr ?
                    element.attr(additionalAttr).toLowerCase() : '';

                if (text.includes(searchString) || attrText.includes(searchString)) {
                    element.removeClass('d-none');
                } else {
                    element.addClass('d-none');
                }
            });
            resolve(true);
        });
    }

    function hideLoader(loader) {
        loader.addClass('d-none');
    }

    function showStatusText(elements, statusContainer) {
        const total = elements.length;
        const hidden = elements.filter('.d-none').length;
        hidden == total
            ? statusContainer.removeClass('text-info').addClass('text-danger')
            : statusContainer.removeClass('text-danger').addClass('text-info');
        const str = `Matched ${total - hidden} out of total ${total}`;
        statusContainer.html(str);
    }

    $('input[find]').on('input', async function () {
        const input = $(this);
        const inputVal = input.val()?.trim();
        const searchElements = $(`${input.attr('find-in')}`);
        const statusContainer = $(`${input.attr('status')}`);
        const loader = $(`${input.attr('loader')}`);

        loader.removeClass('d-none');

        if (inputVal?.length == 0) {
            statusContainer.html('-');
            return await resetSearchElements(searchElements).then(hideLoader(loader));
        }

        const additionalAttr = input.attr('additional-attr');

        return await searchInElements(searchElements, inputVal, additionalAttr)
            .then(showStatusText(searchElements, statusContainer))
            .then(hideLoader(loader));
    });
})(jQuery);
