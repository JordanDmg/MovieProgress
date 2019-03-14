$(".watch-btn").click(function (e) {
    e.preventDefault()
    var apiid = $(this).data("api-id")
    var title = $(this).data("title")
    var state = $(this).find('i').attr("class")
    $.get("view/" + title + "/" + apiid,)
    if (state === 'btn-icon fas fa-eye-slash') {
        $(this).find('i').removeClass("fa-eye-slash")
        $(this).find('i').toggleClass("fa-eye")
        
        //$('.rating#'+apiid).barrating('set', 0)
        
       
        

    } else {
        $(this).find('i').removeClass("fa-eye"),
        $(this).find('i').toggleClass("fa-eye-slash")
        
    }
});