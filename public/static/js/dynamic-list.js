const dynamicList = (function ($, window, main) {
    'use strict';

    let listAjax = undefined;
    let listTimer = undefined;

    /**
     * Time to wait between keystrokes before sending request
     *
     * @constant
     */
    const typegap = 750;

    function getListItem(item, radioName, autofill, emitEvent = null) {
        emitEvent = emitEvent == null ? '' : 'event="' + emitEvent + '"';
        return `<label class='list-group-item cur-pointer'>
            <input class='form-check-input me-1' type='radio' name='${radioName}'
                fill='${autofill}' value='${item.id}' ${emitEvent}>
            ID: <span class='fw-bolder'>${item.id}</span>,
            Name: <span class='fw-bolder'>${item.name}</span>
        </label>`;
    }

    function addParams(input) {
        try {
            const arr = JSON.parse(input.attr('append'));
            let params = {};
            arr.forEach((item) => {
                const tmp = $('input, select, textarea').filter(`[name="${item}"]`).first();
                params[item] = tmp.val();
            });
            params.name = input.val();
            return $.param(params);
        } catch (e) {
            console.log(e);
            return $.param({ name: input.val() });
        }
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

        const endpoint = input.attr('endpoint') + '?' + addParams(input);
        const loader = $(`#${id}-loader`);
        const autofill = input.attr('autofill');
        const listRadioName = input.attr('tmp-name');
        let emitEvent = input[0].hasAttribute('emitevent') ? input.attr('emitevent') : null;

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
                    container.append(getListItem(item, listRadioName, autofill, emitEvent));
                });
            }).fail(() => {
                console.log('Request failed!');
            }).always(() => {
                loader.addClass('d-none');
            });
        }, typegap);
    });

    /**
     * @event click
     * @returns {undefined|boolean}
     */
    $(window.document).on('click', 'input[fill]', function () {
        const input = $(this);
        try {
            $(`#${input.attr('fill')}`).val(input.val());
        } catch (e) {
            console.log('Autofill input not defined. Skipping...');
        }
        if (input[0].hasAttribute('event')) {
            let event = new CustomEvent(input.attr('event'), { detail: {input: input} });
            return window.dispatchEvent(event);
        }
    });
}(jQuery, window, main));
