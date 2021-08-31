const students = (function ($, window) {
    'use strict';

    const form = $('#bulk-students-form');
    const tbody = $('#students-table').find('tbody');

    $('#add-row').on('click', function () {
        const row = tbody.find('[row-clone]').first().clone(false);
        return tbody.append(row);
    });

    $(window.document).on('click', 'table [delete-row]', function () {
        const row = $(this).closest('tr');
        return row.siblings().length > 0 ? row.remove() : false;
    });

    $(window.document).on('input', 'table input[name="roll_number[]"]', function () {
        const currentRow = $(this).closest('tr');
        const rollNumberVal = $(this).val();
        const emailInput = currentRow.find('input[name="email[]"]');
        const append = '@nitsikkim.ac.in';

        if (rollNumberVal?.length == 0) {
            emailInput.val('');
            return;
        }

        emailInput.val((rollNumberVal.substr(0, 7) + append).toLowerCase());
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
