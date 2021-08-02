const globalHandler = (function ($, window) {
    'use strict';

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
        /* prevent other click handlers of same type from running */
        e.stopImmediatePropagation();

        const btn = $(this);

        window.Swal.fire({
            title: btn.attr('alert-title') ?? 'Sure to continue?',
            text: btn.attr('alert-text') ?? 'You won\'t be able to revert this!',
            icon: 'warning',
            timer: btn.attr('alert-timer') ?? null,
            timerProgressBar: true,
            background: btn.attr('alert-bg') ?? undefined,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                if (btn[0].hasAttribute('spoof')) {
                    return spoofMethod(btn, e);
                }
                window.location.href = btn.attr('href');
            }
        });
    });

    $('a[spoof]').on('click', function(e) {
        e.preventDefault();
        spoofMethod($(this), e);
    });

    function spoofMethod(anchorElement, event) {
        const a = anchorElement;
        const form = $('#methodSpoofer');
        const spoofUrl = a.attr('href');
        const spoofMethod = a.attr('spoof-method') ?? 'POST';

        form.attr('action', spoofUrl);
        event.ctrlKey ? form.attr('target', '_blank') : form.attr('target', '_self');
        form.find('input[name="_method"]').val(spoofMethod);
        form.trigger('submit');
    }

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
