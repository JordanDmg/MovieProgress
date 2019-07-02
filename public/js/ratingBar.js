$(function () {
    $('.rating').barrating({
        theme: 'bars-1to10',
        allowEmpty: true,
        
        onSelect: function (value, text, event) {
            var apiid = this.$elem.attr('id')
            iconWatchButtonState = $('#'+apiid+'.btn').attr("class").split(' ')[1]
           console.log(iconWatchButtonState);
            if (iconWatchButtonState === 'btn-light'){
                $('#'+apiid+'.btn-icon').removeClass('btn-light')
                $('#'+apiid+'.btn-icon').toggleClass('btn-secondary')
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
