const students = (function ($, window) {
    'use strict';

    const form = $('#bulk-students-form');
    const tbody = $('#students-table').find('tbody');

    /**
     * @constant HTMLElementString
     */
    const row = `
        <tr>
            <td>
                <input type="text" class="form-control" placeholder="Name"
                    name="name[]" required>
            </td>
            <td>
                <input type="text" class="form-control"
                    placeholder="Roll Number" name="roll_number[]"
                    required>
            </td>
            <td>
                <input type="email" class="form-control"
                    placeholder="Email" name="email[]" required>
            </td>
            <td>
                <span class="material-icons cur-pointer text-danger"
                    delete-row>delete</span>
            </td>
        </tr>
    `;

    $('#add-row').on('click', function () {
        tbody.append(row);
    });

    $(window.document).on('click', 'table [delete-row]', function () {
        $(this).closest('tr').remove();
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
