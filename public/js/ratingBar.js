$(function () {
    $('.rating').barrating({
        theme: 'bars-1to10',
        emptyValue: 0,
        
        onSelect: function (value, text, event) {
            var apiid = this.$elem.attr('id')

            iconWatchButtonState = $('#'+apiid+'.btn-icon').attr("class").split(' ')[2]
           
            if (iconWatchButtonState === 'fa-eye'){
                $('#'+apiid+'.btn-icon').removeClass('fa-eye')
                $('#'+apiid+'.btn-icon').toggleClass('fa-eye-slash')
            }
            if(value){
            $.get("rate/" + apiid + "/" + value + "/" , function( data ) {
                console.log( "Data Loaded: " + data );
              });

            }else {
            
            }
        },
        
    });
});
