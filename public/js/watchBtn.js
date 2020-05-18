

$(".watch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var state = $(this).find('i').attr("class")
    
    $.get("/view/" + apiid)

    if (state === 'notSeen far fa-eye fa-lg') {
        var test = $('.rating#'+apiid).barrating('set',0)
        $(this).find('i').removeClass("notSeen far fa-eye fa-lg")
        $(this).find('i').toggleClass("seen far fa-eye fa-lg")
        $('#'+apiid+'.btn-icon')
        $('#seenText').toggleClass("d-none")

    } else {
        $(this).find('i').removeClass("far fa-eye fa-lg"),
        $(this).find('i').toggleClass("seen notSeen far fa-eye fa-lg")
        $('#seenText').toggleClass("d-none")

        
    }
});