const imageSelector = (function ($, window, main) {
    'use strict';

    const imageInput = $('#profile_image');
    const imagePreview = $('#image_preview');

    imageInput.on('change', function (e) {
        const file = imageInput[0].files[0];

        if (file) {
            const reader = new window.FileReader;
            const image = new window.Image;

            image.onload = () => {
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
