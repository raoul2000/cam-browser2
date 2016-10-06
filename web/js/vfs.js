  var cm=null;
  var selected = {
    'filepath' : null
  };
  $(function() {
      $pageVar = $('#pageVar');
      var urlUpdateFile = $pageVar.data('update-file-url');

      /**
       * Invoek ajax url to update existing file content
       * @param  {string} url URL to call (POST)
       * @param  {string} filepath absolute path of the file to update
       * @param  {string} content  new file content to write
       * @return {boolean}          FALSE
       */
      var updateFile = function(url, filepath, content) {
        if( url ) {
          $.post(
            url,
            {
              'filepath' : filepath,
              'content'  : content
            },
            function(data){
              console.log(data);
            }
          );
        } else {
          console.error("no url available for updating file");
        }
        return false;
      };

      /**
       * User clicks on filename
       */
      $('.view-file-content').on('click', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();

        // clear file content view
        if(cm !== null) {
          cm.getWrapperElement().parentNode.removeChild(cm.getWrapperElement());
          cm = null;
        }
        //$('#selected-file').empty();
        $('#file-content').empty();

        // get info on item to display.
        var $this = $(ev.target);
        var filePath = $this.data('path');
        var fileMimeType = $this.data('mimetype');
        var fileExtension = $this.data('extension');
        var cmOptions = $this.data('cm-options');
        var basename = filePath.split(/[\\/]/).pop();

        console.log(cmOptions);
        console.log("basename = "+basename);

        // update breadcrumb
        if( document.getElementById('selected-file') === null) {
          $('.breadcrumb').append('<li id="selected-file"/>');
        }
        $('#selected-file').html(basename);
        // show content
        selected.filePath = null;
        if( /image\/..*/.exec(fileMimeType) ) {
          var imgSrc = "index.php?"+ $.param({
            'r' : "explorer/view-file-content",
            "path" : filePath
          });
          $('#file-content').html('<img src="'+imgSrc+'" class="img-responsive"/>');
        }
        else if (cmOptions )
        {
          selected.filepath = filePath;
          $.get( "index.php", {
            'r'    : "explorer/view-file-content",
            "path" : filePath
          } , function( data ) {

            cm = CodeMirror( document.getElementById('file-content'), {
              value: data,
              mode:  cmOptions ? cmOptions.mode : "javascript",
              lineNumbers : true,
              extraKeys: {
                "F11": function(cm) {
                  cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                  if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
              }
            });
            CodeMirror.commands.save = function(editor){
              updateFile(urlUpdateFile, selected.filepath, editor.getValue());
              return false;
            };
          });
        }
      });
  });
