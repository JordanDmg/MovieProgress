$(".addList").click(function (e) {
    e.preventDefault()
    id = $(this).data("list-id")
    if ($(this).attr('class') === "addList btn btn-light"){
        
       $.get("/listes/addliste/"+ id)

       $(this).removeClass("btn btn-light")

       $(this).toggleClass("btn btn-secondary")
    }else 
    {
        $.get("/listes/unfollowList/" + id)
        $(this).removeClass("btn btn-secondary")

        $(this).toggleClass("btn btn-light")
    }
    

    

    // var apiid = $(this).data("api-id")
    // var title = $(this).data("title")
    // var posterPath = $(this).data("poster")
    // var state = $(this).find('i').attr("class")
    // $.get("view/" + title + "/" + apiid + posterPath)

    // if (state === 'btn-icon fas fa-eye-slash') {
    //     var test = $('.rating#'+apiid).barrating('set',0)
    //     $(this).find('i').removeClass("fa-eye-slash")
    //     $(this).find('i').toggleClass("fa-eye")

    // } else {
    //     $(this).find('i').removeClass("fa-eye"),
    //     $(this).find('i').toggleClass("fa-eye-slash")
        
    // }
});