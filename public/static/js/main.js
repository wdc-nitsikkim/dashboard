const lsMod = (function (window) {
    this.ls = window.localStorage;

    function state() {
        const tmpKey = "checkLs";
        const tmpVal = "checking-LocalStorage";
        try {
            ls.setItem(tmpKey, tmpVal);
            if (ls.getItem(tmpKey) === tmpVal) {
                unsetKey(tmpKey);
                return true;
            } else {
                return false;
            }
        } catch (e) {
            return false;
        }
    }

    function setKey(key, val) {
        return ls.setItem(key, val);
    }

    function unsetKey(key) {
        return ls.removeItem(key);
    }

    function getKey(key) {
        return ls.getItem(key);
    }

    function clear() {
        return ls.clear();
    }

    return Object.freeze({
        state,
        set: setKey,
        unset: unsetKey,
        get: getKey,
        clear
    });
})(window);

const main = (function ($, window) {
    const breakPoints = {
        sm: 540,
        md: 720,
        lg: 960,
        xl: 1140
    };

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

    function verifyImageRatio(image) {
        return image.height == image.width;
    }

    return Object.freeze({
        breakPoints,
        spoofMethod,
        modifySideNav,
        verifyImageRatio
    });
}(jQuery, window));

const globalHandler = (function ($, window, main) {
    'use strict';

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
                    return main.spoofMethod(btn, e);
                }
                window.location.href = btn.attr('href');
            }
        });
    });

    $('a[spoof]').on('click', function(e) {
        e.preventDefault();
        return main.spoofMethod($(this), e);
    });

    jQuery(function() {
        /* trigger sidenav only for large screens */
        $(window).width() >= main.breakPoints.lg ? main.modifySideNav()
            : console.log('Sidenav trigger cancelled!');

        /* custom readonly radio buttons */
        $(':radio:not(:checked)[readonly]').attr('disabled', true);
    });
})(jQuery, window, main);
