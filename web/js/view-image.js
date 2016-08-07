  $(function() {

      $('img.clickable').on('click', function(ev) {
          // TODO : when showing fillscreen image, hide the toolbar
          var $img = $(ev.target);
          console.log($img.attr('src'));
          $('#img-fullscreen').attr('src', $img.attr('src'));
          $('#grid').hide();
          $('#fullscreen').show();
      });

      $('#btn-back').on('click', function(ev) {
          $('#fullscreen').hide();
          $('#grid').show();
      });

      /**
       * Deletes a single file
       */
      $('.btn-delete').on('click', function(ev) {
          var btn = $(ev.target);
          console.log(btn.data('path'));
          if (confirm("Delete this file ? ")) {
              $.getJSON('delete-single-file.php', {
                  path: btn.data('path')
              }, function(data) {
                  if (data.error) {
                      alert('Error : ' + data.message);
                  } else {
                      btn.closest('.col-md-4').hide('slow');
                  }
              });
          }
      });

      /**
       * Delete all files having last modified date equal to the current date being displayed
       */
      var progressBarDeleteWrapper = $('#wrap-progress-delete-bar');
      var progressBarDeleteMsg = $('#progress-delete-msg');
      var progressBar = $('#progress-delete-bar');
      var errorDeleteCount = 0;
      var btnDeleteAll = $('#btn-delete-all');

      function callDeleteSingleFile(path, success, error, finish) {
          return function() {

              var defer = $.Deferred();
              $.getJSON('delete-single-file.php', {
                      "path": path
                  }, function(data) {
                      if (data.error) {
                          error();
                      } else {
                          success();
                      }
                  })
                  .done(function() {
                      finish();
                      defer.resolve();
                  });
              return defer.promise();
          };
      }

      btnDeleteAll.prop('disabled', false);
      btnDeleteAll.on('click', function(ev) {
          var date = $(ev.target).data('date');
          if (confirm("WARNING : you are about to delete all files for the date '" + date + "'.\nAre you sure ?")) {
              errorDeleteCount = 0;
              progressBarDeleteWrapper.show();
              btnDeleteAll.prop('disabled', true);

              var btnList = $('.btn-delete');
              var base = $.when({});
              btnList.each(function(index, button) {
                  var thisButton = $(button);
                  var path = thisButton.data("path");
                  base = base.then(callDeleteSingleFile(
                      path,
                      function() {
                          thisButton.closest('.col-md-4').hide();
                          var percent = Math.floor(100 * (index + 1) / btnList.length);
                          progressBarDeleteMsg.text("" + percent + "%");
                          progressBar.css("width", percent + "%");
                      },
                      function() {
                          errorDeleteCount++;
                      },
                      function() {
                          if (index == btnList.length - 1) {
                              if (errorDeleteCount == 0) {
                                  $('#grid').hide();
                                  progressBarDeleteWrapper.hide();
                                  alert('All image have been deleted for this day.');
                                  document.location = "index.php";
                              } else {
                                  btnDeleteAll.prop('disabled', false);
                                  setTimeout(function() {
                                      progressBarDeleteWrapper.hide();
                                      progressBar.css("width", "0px");
                                  }, 500);
                              }
                          }
                      }
                  ));
              });
          }
      });

      /**
       * Navigates to the previous index page
       */
      $('#btn-back-to-index').on('click', function(ev) {
          document.location = "index.php";
      });
  })
