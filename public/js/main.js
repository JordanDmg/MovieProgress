$( ".mousehover" ).hover(
    function() {
      $( this ).addClass( "hover" );
     $(this).children('i').show()
    }, function() {
      $( this ).removeClass( "hover" );
     $(this).children('i').hide()

    }
  );

