(function ($, window, tableReorder) {
    'use strict';

    const form = $('#order-people');
    const table = form.find('table[orderable]');

    form.on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const order = tableReorder.getOrder(table, 'profile_id');

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: {
                'order': JSON.stringify(order)
            },
            beforeSend: () => {
                submitBtn.attr('disabled', true);
            }
        }).done(() => {

        }).fail(() => {
            console.error('Request failed!');
        }).always(() => {
            submitBtn.attr('disabled', false);
        });
    });
})(jQuery, window, tableReorder);
