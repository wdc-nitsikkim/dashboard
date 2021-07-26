const globalHandler = (function ($, window) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('a[confirm]').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);

        window.Swal.fire({
            title: btn.attr('alert-title') ?? 'Sure to continue?',
            text: btn.attr('alert-text') ?? 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = btn.attr('href');
            }
        });
    });
})(jQuery, window);
