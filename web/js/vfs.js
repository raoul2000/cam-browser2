// the current Code Mirror instance or NULL if no
// Code Mirraor instance exist
var cm = null;
// info describing the file being edited or NULL if no
// file editor is displayed
var selected = {
    'filepath': null
};

$(function() {
    $pageVar = $('#pageVar');
    var urlUpdateFile = $pageVar.data('update-file-url');

    /**
     * Invoek ajax url to update existing file content.
     *
     * @param  {string} url URL to call (POST)
     * @param  {string} filepath absolute path of the file to update
     * @param  {string} content  new file content to write
     * @return {boolean}          FALSE
     */
    var updateFile = function(url, filepath, content, cb) {
        $('.breadcrumb').append('<li id="update-status">saving ... </li>');
        if (url) {
            $.post(
                url, {
                    'filepath': filepath,
                    'content': content
                },
                function(data) {
                    console.log(data);
                }
            ).fail(function() {
                alert('save failed');
            }).always(function() {
                $('#update-status').remove();
            });
        } else {
            console.error("no url available for updating file");
        }
        return false;
    };

    /**
     * User clicks on filename to preview or edit its content
     */
    $('.view-file-content').on('click', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();

        // clear file content view and reset the current code mirror instance
        if (cm !== null) {
            cm.getWrapperElement().parentNode.removeChild(cm.getWrapperElement());
            cm = null;
        }
        $('#file-content').empty();

        // get info on item to display.
        var $this = $(ev.target);
        var filePath = $this.data('path');
        var fileMimeType = $this.data('mimetype');
        var fileExtension = $this.data('extension');
        var cmOptions = $this.data('cm-options');
        var basename = filePath.split(/[\\/]/).pop();

        console.log(cmOptions);

        // update breadcrumb
        if (document.getElementById('selected-file') === null) {
            $('.breadcrumb').append('<li id="selected-file"/>');
        }
        $('#selected-file').html(basename);

        // show content
        selected.filePath = null;
        if (/image\/..*/.exec(fileMimeType)) {
            var imgSrc = "index.php?" + $.param({
                'r': "explorer/view-file-content",
                "path": filePath
            });
            $('#file-content').html('<img src="' + imgSrc + '" class="img-responsive"/>');
        } else if (cmOptions) {
            selected.filepath = filePath;
            // GET file content and on success, setup the code Mirror
            // instance with it.
            $.get("index.php", {
                'r': "explorer/view-file-content",
                "path": filePath
            }, function(data) {
                var options = {
                    value: data,
                    lineNumbers: true,
                    extraKeys: {
                        "F11": function(cm) {
                            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                        },
                        "Esc": function(cm) {
                            if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                        }
                    }
                };
                //  merge CM options defined server side and default (client side) options
                $.extend(options, cmOptions);

                // instanciate de Code Mirror Editor
                cm = CodeMirror(document.getElementById('file-content'), options);

                // Define the update file content function.
                CodeMirror.commands.save = function(editor) {
                    if (editor.isClean()) {
                        console.log("save not needed");
                    } else {
                        updateFile(urlUpdateFile, selected.filepath, editor.getValue());
                    }
                    return false;
                };
            }); // end of $.get
        }
    });
});
