const lsMod = (function (window) {
    'use strict';

    const ls = window.localStorage;

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

const main = (function ($, window, ls) {
    'use strict';

    const breakPoints = {
        sm: 540,
        md: 720,
        lg: 960,
        xl: 1140
    };

    const localKeys = {
        sidenav: 'sidenav-collapse'
    };

    const appConstants = {
        sidenav: $('#sidebarMenu'),
        sidenavToggleBtn: $('#sidebar-toggle')
    };

    function fillContainer(container, html = '') {
        container.html(html);
    }

    function getSpanMsg(html, classes = null) {
        const defaultClasses = 'text-info small';
        return `<span class="${classes ?? defaultClasses}">${html}</span>`;
    }

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

    function getMainContentImageDataUrl() {
        const options = {
            width: $(window.document)[0].scrollWidth,
            height: $(window.document)[0].scrollHeight
        };

        window.html2canvas($('main.content')[0], options).then(function (canvas) {
            return canvas.toDataURL();
        });
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

    function loadSidenavPreference() {
        const val = ls.get(localKeys.sidenav);
        if (val == 'collapsed') {
            appConstants.sidenavToggleBtn.find('span').html('menu');
            appConstants.sidenav.addClass('contracted');
        }
    }

    function saveSidenavPreference(status) {
        ls.set(localKeys.sidenav, status);
    }

    function loadLocalPreferences() {
        loadSidenavPreference();
    }

    return Object.freeze({
        breakPoints,
        appConstants,
        getSpanMsg,
        spoofMethod,
        modifySideNav,
        fillContainer,
        verifyImageRatio,
        loadLocalPreferences,
        saveSidenavPreference,
        pageImage: getMainContentImageDataUrl
    });
}(jQuery, window, lsMod));

const globalHandler = (function ($, window, main) {
    'use strict';

    let sidenavHoverToggle = false;
    const sidenav = main.appConstants.sidenav;
    const sidenavToggleBtn = main.appConstants.sidenavToggleBtn;

    sidenavToggleBtn.on('click', () => {
        sidenav.toggleClass('contracted');
        if (sidenav.hasClass('contracted')) {
            main.saveSidenavPreference('collapsed');
            sidenavToggleBtn.find('span').html('menu');
        } else {
            main.saveSidenavPreference('');
            sidenavToggleBtn.find('span').html('menu_open');
        }
    });

    sidenav.on('mouseenter', function () {
        if (sidenav.hasClass('contracted')) {
            sidenav.removeClass('contracted');
            sidenavHoverToggle = true;
        }
    }).on('mouseleave', function () {
        if (sidenavHoverToggle) {
            sidenav.addClass('contracted');
            sidenavHoverToggle = false;
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(window.document).on('ajaxError', function (e, xhr) {
        if (xhr.status == 300) {
            window.open(xhr.getResponseHeader('Location'), '_blank');
        }
    });

    $(window.document).on('ajaxSuccess', function (e, xhr) {
        if (typeof xhr.responseJSON == 'undefined') {
            return;
        }

        const fnList = {
            'reload': option => {
                option ? window.location.reload() : false;
            },
            'redirect': location => {
                window.location.href = location;
            }
        };

        for (const key in xhr.responseJSON) {
            if (typeof fnList[key] === 'function') {
                fnList[key](xhr.responseJSON[key]);
            }
        }
    });

    $('a[confirm], button[confirm]').on('click', function (e, bypass = false) {
        const btn = $(this);

        if (bypass) {
            e.preventDefault();
            btn.off('click');
            btn[0].click();
            return;
        }

        e.preventDefault();
        /* prevent other click handlers of same type from running */
        e.stopImmediatePropagation();

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
                btn.trigger('click', true);
            }
        });
    });

    $('a[spoof]').on('click', function (e) {
        e.preventDefault();
        return main.spoofMethod($(this), e);
    });

    jQuery(() => {
        main.loadLocalPreferences();

        /* trigger sidenav only for large screens */
        $(window).width() >= main.breakPoints.lg ? main.modifySideNav()
            : console.log('Sidenav trigger cancelled!');

        /* custom readonly radio buttons */
        $(':radio:not(:checked)[readonly]').attr('disabled', true);
    });
})(jQuery, window, main);
