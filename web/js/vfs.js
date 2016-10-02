  var cm=null;
  $(function() {

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
        var mimeHigh = fileMimeType.split('/')[0];

        var basename = filePath.split(/[\\/]/).pop();
        console.log("path = "+filePath+" MIME = "+fileMimeType+ " mimeHigh = "+mimeHigh);
        console.log("basename = "+basename);

        // update breadcrumb
        if( document.getElementById('selected-file') === null) {
          $('.breadcrumb').append('<li id="selected-file"/>');
        }
        $('#selected-file').html(basename);

        // show content

        if( mimeHigh === 'image') {
          var imgSrc = "index.php?"+ $.param({
            'r' : "explorer/view-file-content",
            "path" : filePath
          });
          $('#file-content').html('<img src="'+imgSrc+'" class="img-responsive"/>');
        }
        else if (mimeHigh === 'text' || fileMimeType == "application/xml" ||
                ['php', 'js'].indexOf(fileExtension) != -1 )
        {
          $.get( "index.php", {
            'r'    : "explorer/view-file-content",
            "path" : filePath
          } , function( data ) {
            cm = CodeMirror( document.getElementById('file-content'), {
              value: data,
              mode:  "javascript",
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
          });
        }
      });
  });
