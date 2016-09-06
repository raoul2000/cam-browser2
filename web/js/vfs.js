  $(function() {
    $('.view-file-content').on('click',function(ev){
      ev.stopPropagation();
      ev.preventDefault();
      var $this = $(ev.target);
      console.log($this.data('path'));
      
    });
  });
