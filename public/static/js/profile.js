const profileHandler = (function ($, window) {
    'use strict';

    const triggerCopyInput = $('#copy_user_data');
    const copyFromAttr = 'data-account-value';
    const saveOldAttr = 'data-old-value';

    triggerCopyInput.on('change', function (e) {
        const inputs = $(this).closest('form').find('input[type="text"], input[type="email"], input[type="number"]')
            .filter(`[${copyFromAttr}]`);
        const state = $(this).prop('checked');

        inputs.each(function () {
            const input = $(this);
            if (state) {
                input.attr(saveOldAttr, input.val());
                input.val(input.attr(copyFromAttr));
            } else {
                input.val(input.attr(saveOldAttr));
            }
        });
    });
}(jQuery, window));
