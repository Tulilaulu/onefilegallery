<script>
(function($){
  $(function(){
  function showSingleItem (item){
    $('#fade').fadeIn(); 
    $('#objectwrapper').fadeIn(); 
    var extension = item.substr( (item.lastIndexOf('.') +1) );
    if (extension == "png" || extension == "jpg" || extension == "gif"){
      showImage(item);
    }
    else{
      showFile(item);
    }
  }
  
  function showImage(item){
    $('#objectwrapper>a').hide();
    $('#objectwrapper>img').attr('src', item);
    $('#objectwrapper>img').fadeIn();
  }

  function showFile(item){
    $('#objectwrapper>img').hide();
    $('#objectwrapper>a').attr('href', item);
    $('#objectwrapper>a').css('display', 'block');
  }


  //url is the url of the single element to be displayed if there is one
  if (typeof(url) != "undefined"){
    showSingleItem(url);
  }

  $('#objectwrapper').click(function(){
    var current = window.location.pathname;
    var lastslash = current.lastIndexOf("/");
//    $('#objectwrapper').fadeOut(400);
  //  $('#fade').fadeOut(200, function(){
      window.location = current.substr(0, lastslash);
//    });
  });
});
})(jQuery);
</script>
