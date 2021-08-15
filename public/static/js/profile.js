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
}(jQuery, window, main));

const editorJsInit = (function ($, window, ls) {
    'use strict';

    const tools = {
        header: {
            class: window.Header,
            config: {
                levels: [2, 3, 4, 5],
                defaultLevel: 4
            },
            inlineToolbar: true
        },
        delimiter: window.Delimiter,
        table: {
            class: window.Table,
            inlineToolbar: true
        },
        list: {
            class: window.List,
            inlineToolbar: true
        },
        marker: window.Marker,
        inlineCode: InlineCode,
        code: CodeTool
    };
    const holderId = 'publications-editor';

    const editor = new window.EditorJS({
        holder: holderId,
        tools: tools,
        placeholder: 'Add your publications & all other meritorious achievements/activites here',
        onChange: storeData,
        onReady: editorReady
    });

    const statusContainer= $('#editor_status');
    const localRestoreBtn = $('#editor_local_restore');
    const serverRestoreBtn = $('#editor_server_restore');
    const resetEditorBtn = $('#editor_failsafe');
    const localStorageKey = holderId;
    const formInput = $('#publications');

    resetEditorBtn.on('click', () => {
        editor.clear();
        return statusMessage('success', 'Editor reinitialized');
    });

    serverRestoreBtn.on('click', () => {
        if (loadData(formInput.attr('original'))) {
            return statusMessage('success', 'Copied server data');
        }
        editor.clear();
        return statusMessage('fail', 'Data empty or corrupt!');
    });

    localRestoreBtn.on('click', function(e) {
        if (loadData(ls.get(localStorageKey))) {
            /* 'onChange' event is not fired when restoring data from localStorage
                so calling 'storeData' manually */
            storeData();
            return statusMessage('success', 'Loaded from localstorage');
        }
        loadData(defaultObj);
        return statusMessage('fail', 'Data empty or corrupt!');
    });

    function storeData() {
        editor.save().then(savedData => {
            if (savedData.blocks.length == 0) {
                formInput.val('');
                return;
            }
            json = JSON.stringify(savedData);
            ls.set(localStorageKey, json);
            formInput.val(json);
            statusMessage('success', 'Saved');
        });
    };

    function editorReady() {
        $(`#${holderId}`).removeClass('d-none');

        loadData(formInput.val());
        statusMessage('success', 'Editor loaded');
    }

    function loadData(jsonString) {
        try {
            let json = JSON.parse(jsonString) ?? undefined;
            if (typeof json === 'undefined') {
                throw new Error('Data empty or corrupt!');
            } else if (json.blocks.length == 0) {
                throw new Error('Empty data');
            }
            editor.render(json);
            return true;
        } catch (e) {
            return false;
        }
    }

    let timer = null;
    function statusMessage(status, msg) {
        statusContainer.removeClass(status == 'success' ? 'text-danger' : 'text-success')
            .addClass(status == 'success' ? 'text-success' : 'text-danger').html(msg);
        clearTimeout(timer);
        timer = setTimeout(() => { statusContainer.html('') }, 2000);
    }

    return editor;
}(jQuery, window, lsMod));
