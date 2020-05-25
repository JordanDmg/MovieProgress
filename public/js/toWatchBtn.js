
$(".toWatch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var state = $(this).find('i').attr("class")
    $.get("/toWatch/" + apiid)
    console.log(state.split(' ').pop())
    if (state.split(' ').pop() === 'fas') {
        var test = $('.rating#'+apiid).barrating('set',0)
        $(this).find('i').removeClass("fas")
        $(this).find('i').toggleClass("far")

    } else {
        $(this).find('i').removeClass("far"),
        $(this).find('i').toggleClass("fas")
        
    }
});