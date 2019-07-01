

$(".watch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var state = $(this).find('i').attr("class")
    
    $.get("/view/" + apiid)

    if (state === 'btn btn-secondary') {
        var test = $('.rating#'+apiid).barrating('set',0)
        $(this).find('i').removeClass("btn btn-secondary")
        $(this).find('i').toggleClass("btn btn-light")

    } else {
        $(this).find('i').removeClass("btn btn-light"),
        $(this).find('i').toggleClass("btn btn-secondary")
        
    }
});