const subjects = (function ($, window) {
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

    /**
     * Custom event 'subjectChosen' is fired when subject is chosen via custom dynamic list
     */
    $(window).on('subjectChosen', function (e) {
        const input = e.detail.input;
        tbody.children('tr').last().find('input[name="subject_id[]"]').val(input.val());
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
