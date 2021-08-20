const feedback = (function ($, window) {
    'use strict';

    const feedbackModal = $('#feedback-modal');
    const feedbackForm = feedbackModal.find('form');
    const starSelector = '[star-rate]';
    const starRatingText = $('#star-rating-text');
    const starContainer = $('#feedback-star-rating');
    const clearStarRatingBtn = $('#clear-star-rating');
    const starRatingInput = feedbackModal.find('input[name="rating"]');

    function addActiveClass(current) {
        const html = {
            active: 'star',
            inactive: 'star_border'
        };
        current.html(html.active).prevAll(starSelector).html(html.active);
        current.nextAll(starSelector).html(html.inactive);
    }

    starContainer.on('click', starSelector, function () {
        const index = $(this).index();
        addActiveClass($(this));
        starRatingText.html(index + 1);
        starRatingInput.val(index + 1);
    });

    clearStarRatingBtn.on('click', function () {
        starContainer.find(starSelector).html('star_border');
        starRatingText.html('-');
        starRatingInput.val('');
    });

    feedbackForm.on('submit', function (e) {
        e.preventDefault();

        const submitBtn = feedbackForm.find('button[type="submit"]');
        const data = new FormData(feedbackForm[0]);

        $.ajax({
            url: feedbackForm.attr('action'),
            method: feedbackForm.attr('method'),
            data: data,
            processData: false,
            contentType: false,
            beforeSend: () => {
                submitBtn.attr('disabled', true);
                feedbackModal.modal('hide');
            }
        }).done(() => {
            window.Swal.fire({
                title: 'Thanks',
                text: 'Your feedback was submitted successfully',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true
            });
        }).fail(() => {
            window.Swal.fire({
                title: 'Submission failed!',
                text: 'Your feedback could not be saved!',
                icon: 'error',
                timer: 3000,
                timerProgressBar: true
            });
        }).always(() => {
            submitBtn.attr('disabled', false);
        });
    });
})(jQuery, window);
