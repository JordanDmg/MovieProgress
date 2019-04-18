$(function () {
    $('.rating').barrating({
        theme: 'bars-1to10',
        allowEmpty: true,
        emptyValue: 0,
        
        onSelect: function (value, text, event) {
            var apiid = this.$elem.attr('id')
            var title = this.$elem.attr('data-title')
            iconWatchButtonState = $('#'+apiid+'.btn-icon').attr("class").split(' ')[2]
            if (iconWatchButtonState === 'fa-eye'){
                $('#'+apiid+'.btn-icon').removeClass('fa-eye')
                $('#'+apiid+'.btn-icon').toggleClass('fa-eye-slash')
            }
            $.get("rate/" + apiid + "/" + value + "/" + title )
        }
    });
});
