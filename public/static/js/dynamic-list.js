const dynamicList = (function ($, window, main) {
    'use strict';

    let listAjax = undefined;
    let listTimer = undefined;

    /* wait for given gap between keyboard strokes before sending request */
    const typegap = 750;

    function getListItem(item, radioName, autofill) {
        return `<label class='list-group-item'>
            <input class='form-check-input me-1' type='radio' name='${radioName}'
                fill='${autofill}' value='${item.id}'>
            ID: <span class='fw-bolder'>${item.id}</span>,
            Name: <span class='fw-bolder'>${item.name}</span>
        </label>`;
    }

    $('input[dynamic-list]').on('input', function () {
        const input = $(this);
        const id = input.attr('dynamic-list');
        const container = $('#' + id);
        const val = input.val()?.trim();
        const defaultText = main.getSpanMsg('Atleast a letter is required');

        clearTimeout(listTimer);
        if (listAjax) {
            listAjax.abort();
        }

        if (typeof val == 'undefined' || val == '' || val.length == 0) {
            return main.fillContainer(container, defaultText);
        }

        const endpoint = input.attr('endpoint') + '?name=' + val;
        const loader = $(`#${id}-loader`);
        const autofill = input.attr('autofill');
        const listRadioName = input.attr('tmp-name');

        listTimer = setTimeout(() => {
            listAjax = $.ajax({
                url: endpoint,
                dataType: 'json',
                beforeSend: () => {
                    loader.removeClass('d-none');
                }
            }).done(response => {
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
            }).fail(() => {
                console.log('Request failed!');
            }).always(() => {
                loader.addClass('d-none');
            });
        }, typegap);
    });

    $(window.document).on('click', 'input[fill]', function () {
        const input = $(this);
        $(`#${input.attr('fill')}`).val(input.val());
    });
}(jQuery, window, main));
