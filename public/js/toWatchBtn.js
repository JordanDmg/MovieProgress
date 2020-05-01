$(".toWatch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var state = $(this).find('i').attr("class")
    $.get("/toWatch/" + apiid)

    if (state === 'fas fa-bookmark fa-2x') {
        var test = $('.rating#'+apiid).barrating('set',0)
        $(this).find('i').removeClass("fas fa-bookmark fa-2x")
        $(this).find('i').toggleClass("far fa-bookmark fa-2x")

    } else {
        $(this).find('i').removeClass("far fa-bookmark fa-2x"),
        $(this).find('i').toggleClass("fas fa-bookmark fa-2x")
        
    }
});