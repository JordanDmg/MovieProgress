$(function () {
    $('.rating').barrating({
        theme: 'bars-1to10',
        emptyValue: 0,
        onSelect: function (value, text, event) {
            var apiid = this.$elem.attr('id')
            var title = this.$elem.attr('data-title')
            $.get("rate/" + apiid + "/" + value + "/" + title )
        }
    });
});