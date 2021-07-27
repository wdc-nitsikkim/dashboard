const globalHandler = (function ($, window) {
    const breakPoints = {
        sm: 540,
        md: 720,
        lg: 960,
        xl: 1140
    };

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

    function modifySideNav() {
        const active = $('li.nav-item.active').first();
        let subMenu = active.closest('div.multi-level.collapse');
        if (subMenu.length > 0) {
            const id = subMenu.attr('id');
            const menuBtn = $(`[data-bs-target='#${id}']`);
            menuBtn.trigger('click');
            console.log('Sidenav triggered!');
            return true;
        }

        console.log('Sidenav trigger not required!');
        return false;
    }

    jQuery(function() {
        $(window).width() >= breakPoints.lg ? modifySideNav() : console.log('Sidenav trigger cancelled!');
    });
})(jQuery, window);
