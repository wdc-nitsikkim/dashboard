const tableReorder = (function ($) {
    'use strict';

    function addIndexClasses(...elements) {
        elements.forEach(element => {
            element.removeClass('alert-success alert-danger');
            const currentIndex = element.index() + 1;  /* '+1' beacuse of 0-based index */
            const originalIndex = Number(element.attr('order-index'));

            if (currentIndex == originalIndex) {
                return;
            } else if (currentIndex < originalIndex) {
                element.addClass('alert-success');
            } else {
                element.addClass('alert-danger')
            }
        });
    }

    $('table[orderable]').on('click', '[order-up], [order-down]', function () {
        const btn = $(this);
        const tableRow = btn.closest('tr');

        if (btn[0].hasAttribute('order-up')) {
            tableRow.prev().before(tableRow);
            addIndexClasses(tableRow, tableRow.next());
        } else {
            tableRow.next().after(tableRow);
            addIndexClasses(tableRow, tableRow.prev());
        }
    });

    function getOrder(table, key) {
        let data = [];
        const tableRows = table.find('tr[order-id]');
        tableRows.each(function (index) {
            const row = $(this);
            const tmp = {
                [key]: row.attr('order-id'),
                order: index + 1
            };
            data.push(tmp);
        });
        return data;
    }

    return Object.freeze({
        getOrder
    });
})(jQuery);
