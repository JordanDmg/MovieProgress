$(function () {
    $('.rating').barrating({
        theme: 'bars-1to10',
        allowEmpty: true,
        
        onSelect: function (value, text, event) {
            var apiid = this.$elem.attr('id')
            console.log($('#'+apiid+'.btn').attr("class").split(' ')[1])
            iconWatchButtonState = $('#'+apiid+'.btn').attr("class").split(' ')[1]
           
            if (iconWatchButtonState === 'fa-eye'){
                $('#'+apiid+'.btn-icon').removeClass('fa-eye')
                $('#'+apiid+'.btn-icon').toggleClass('fa-eye-slash')
            }
            if(value){
            $.get("/rate/" + apiid + "/" + value + "/" , function( data ) {
                console.log( "Data Loaded: " + data );
              });

            }else {
            
            }
        },
        
    });
});
