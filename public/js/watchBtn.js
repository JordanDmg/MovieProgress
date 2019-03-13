$(".watch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var title = $(this).data("title")
    var state = $(this).find('i').attr("class")
    $.get("view/" + title + "/" + apiid,)
    if (state === 'fas fa-eye-slash') {
        $(this).find('i').removeClass("fas fa-eye-slash")
        $(this).find('i').toggleClass("fas fa-eye")
    } else {
        $(this).find('i').removeClass("fas fa-eye")
        $(this).find('i').toggleClass("fas fa-eye-slash")
    }
});