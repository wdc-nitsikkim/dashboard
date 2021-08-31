const students = (function ($, window) {
    'use strict';

    const form = $('#bulk-subjects-form');
    const tbody = $('#subjects-table').find('tbody');

    $('#add-row').on('click', function () {
        const row = tbody.find('[row-clone]').first().clone(false);
        tbody.append(row);
    });

    $(window.document).on('click', 'table [delete-row]', function () {
        const row = $(this).closest('tr');
        return row.siblings().length > 0 ? row.remove() : false;
    });

    form.on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const data = new FormData(form[0]);

        jsonFormErrorHandler.removeClass(form);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: data,
            processData: false,
            contentType: false,
            beforeSend: () => {
                submitBtn.attr('disabled', true);
            }
        }).done(() => {

        }).fail(xhr => {
            if (typeof xhr.responseJSON !== 'undefined') {
                const errors = jsonFormErrorHandler.reformat(xhr.responseJSON.errors);
                jsonFormErrorHandler.addClass(tbody.children(), errors);
            }
        }).always(() => {
            submitBtn.attr('disabled', false);
        });
    });
})(jQuery, window, jsonFormErrorHandler);
