const profileHandler = (function ($, window, main) {
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

    const imageInput = $('#profile_image');
    const imagePreview = $('#image_preview');

    imageInput.on('change', function (e) {
        const file = imageInput[0].files[0];

        if (file) {
            const reader = new window.FileReader;
            const image = new window.Image;

            image.onload = () => {
                let status = true;

                if (!main.verifyImageRatio(image)) {
                    window.Swal.fire({
                        title: 'A 1:1 (square) image is required!',
                        text: 'Chosen image may be displayed weirdly',
                        showDenyButton: true,
                        confirmButtonText: 'Choose another',
                        denyButtonText: 'Continue?',
                    }).then((result) => {
                        if (result.isDenied) {
                            imagePreview.attr('src', reader.result)
                            return;
                        }
                        imageInput.val(null).trigger('change');
                    });
                } else {
                    imagePreview.attr('src', reader.result);
                }
            }

            reader.onload = () => {
                image.src = reader.result;
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.attr('src', imagePreview.attr('original-src'));
        }
    });
}(jQuery, window, main));
