
$( ".mousehover" ).hover(
    function() {
      $( this ).addClass( "hover" );
     $(this).children('i').show()
    }, function() {
      $( this ).removeClass( "hover" );
     $(this).children('i').hide()

    }
  );
  $(document).mouseup(function(e) 
  {
      var container = $("#test");
  
      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) 
      {
          container.hide();
      }
  });
  $(document).click(function(e) {

    // check that your clicked
    // element has no id=info
  
    if( e.target.id != 'search_frame' && e.target.id != 'homeSearch' ) {
      $("#search_frame").hide();
    }
  });