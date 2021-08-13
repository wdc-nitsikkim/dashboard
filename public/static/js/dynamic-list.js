const dynamicList = (function ($, window, main) {
    'use strict';

    function getListItem(item, radioName, autofill) {
        return `<label class='list-group-item'>
            <input class='form-check-input me-1' type='radio' name='${radioName}'
                fill='${autofill}' value='${item.id}'>
            ID: <span class='fw-bolder'>${item.id}</span>,
            Name: <span class='fw-bolder'>${item.name}</span>
        </label>`;
    }

    $('input[dynamic-list]').on('input', function(e) {
        const input = $(this);
        const id = input.attr('dynamic-list');
        const container = $('#' + id);
        const val = input.val()?.trim();
        const defaultText = main.getSpanMsg('Atleast a letter is required');

        if (typeof val == 'undefined' || val == '' || val.length == 0) {
            return main.fillContainer(container, defaultText);
        }

        const endpoint = input.attr('endpoint') + '?name=' + val;
        const loader = $(`#${id}-loader`);
        const autofill = input.attr('autofill');
        const listRadioName = input.attr('tmp-name');

        $.ajax({
            url: endpoint,
            dataType: 'json',
            beforeSend: () => {
                loader.removeClass('d-none');
            }
        }).done(function(response) {
            if (response.length == 0) {
                return main.fillContainer(
                    container,
                    main.getSpanMsg('No results!', 'text-danger small')
                );
            }

            main.fillContainer(container);

            response.forEach(item => {
                container.append(getListItem(item, listRadioName, autofill));
            });
        }).fail(function() {
            console.log('Request failed!');
        }).always(function() {
            loader.addClass('d-none');
        });
    });

    $(window.document).on('click', 'input[fill]', function(e) {
        const input = $(this);
        $(`#${input.attr('fill')}`).val(input.val());
    });
}(jQuery, window, main));
