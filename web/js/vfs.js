    var cm=null;
  $(function() {

      $('.view-file-content').on('click', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();

        if(cm !== null) {
          cm.toTextArea();
          cm = null;
        }

        var $this = $(ev.target);
        var filePath = $this.data('path');
        var fileMimeType = $this.data('mimetype');
        var fileExtension = $this.data('extension');
        var mimeHigh = fileMimeType.split('/')[0];
        console.log("path = "+filePath+" MIME = "+fileMimeType+ " mimeHigh = "+mimeHigh);

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
            $('#file-content').html('<textarea  id="txt-content-editor" class="form-control" rows="15" style="display:none"></textarea>' );
            $('#txt-content-editor').text(data);

            var txtAreaEl = document.getElementById('txt-content-editor');
            cm = CodeMirror.fromTextArea(txtAreaEl,{"mode" : "javascript"});


          });
        }
      });
  });
