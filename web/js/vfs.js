  $(function() {
      $('.view-file-content').on('click', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();
        var $this = $(ev.target);
        var filePath = $this.data('path');
        var fileMimeType = $this.data('mimetype');
        var mimeHigh = fileMimeType.split('/')[0];
        console.log("path = "+filePath+" MIME = "+fileMimeType+ " mimeHigh = "+mimeHigh);

        if( mimeHigh === 'image') {
          var imgSrc = "index.php?"+ $.param({
            'r' : "explorer/view-file-content",
            "path" : filePath
          });
          $('#file-content').html('<img src="'+imgSrc+'" class="img-responsive"/>');
        }else if (mimeHigh === 'text' || fileMimeType == "application/xml") {
          $.get( "index.php", {
            'r' : "explorer/view-file-content",
            "path" : filePath
          } , function( data ) {
            $('#file-content').html('<textarea  class="form-control">' + data + '</textarea>' );
          });
        }
      });
  });
