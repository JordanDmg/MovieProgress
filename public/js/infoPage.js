$(".hidebutton").click(function (e) {
    // e.preventDefault()
    // console.log($(this).next());
    $(this).next().slideToggle(200);
})