
$(function () {
    $('.rating-bar').barrating({
        theme: 'bars-1to10',
        allowEmpty: true,
        
        onSelect: function (value, text, event) {
            var apiid = this.$elem.attr('id')
            // iconWatchButtonState = $('#'+apiid+'.btn').attr("class").split(' ')[1]
            iconWatchButtonState = $('#'+apiid+'.seenIcone').attr("class").split(' ')[1];
            if (iconWatchButtonState === 'notSeen'){
                $('#'+apiid+'.seenIcone').removeClass('notSeen')
                $('#'+apiid+'.seenIcone').toggleClass('seen')
                $('#seenText').toggleClass("d-none")

            }
            if(value){
            $.get("/rate/" + apiid + "/" + value + "/" , function( data ) {
                console.log( "Data Loaded: " + data );
              });

            }else {
                $.get("/removeRate/" + apiid  + "/" , function( data ) {
                    console.log( "Data Loaded: " + data );
                  });
            }
        },
        
    });
});







