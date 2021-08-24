const results = (function ($, window) {
    'use strict';

    const tableCompactBtn = $('#toggle-compact');
    const toggleEditBtn = $('#toggle-edit');
    const table = $('#students');
    const form = table.closest('form');
    const elements = table.find('tbody > tr > td > input').closest('td');

    tableCompactBtn.on('click', function () {
        return $('table').toggleClass('table-sm')
    });

    toggleEditBtn.on('click', function () {
        elements.find(':not(input)').toggleClass('d-none');
        elements.find('input').toggleClass('d-none');
    });

    form.on('submit', function (e) {
        e.preventDefault();

        const submitBtn = form.find('button[type="submit"]');
        const data = new FormData(form[0]);

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

        }).fail(() => {
            console.log('Request failed!');
            window.Swal.fire({
                title: 'An unknown error occurred!',
                text: 'Failed to save. Try again later!',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
            });
        }).always(() => {
            submitBtn.attr('disabled', false);
        });
    });
})(jQuery, window);
