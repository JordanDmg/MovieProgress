$(".addList").click(function (e) {
    e.preventDefault()
    id = $(this).data("list-id")
    if ($(this).attr('class') === "addList btn btn-primary"){
        
       $.get("/listes/addliste/"+ id)
    
    }else 
    {
        $.get("/listes/unfollowList/" + id)

    }
    $(this).toggleClass("btn-dark")
    

    

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