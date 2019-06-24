

$(".watch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var state = $(this).find('i').attr("class")
    $.get("view/" + apiid)

    if (state === 'btn-icon fas fa-eye-slash') {
        var test = $('.rating#'+apiid).barrating('set',0)
        $(this).find('i').removeClass("fa-eye-slash")
        $(this).find('i').toggleClass("fa-eye")

    } else {
        $(this).find('i').removeClass("fa-eye"),
        $(this).find('i').toggleClass("fa-eye-slash")
        
    }
});