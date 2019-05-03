$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');


        $('li a span').each(function () {
            $(this).toggle()
        })
        $('.longTitle, .shortTitle').toggle()
        $('.fa-chevron-left, .fa-chevron-right').toggle()
       
    });

});



$(window).resize(function() {
    if ($( window ).width() <= 768 && !$('#sidebar').hasClass('active')  ) {
        console.log('bonjour')
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');

        $('li a span').each(function () {
            $(this).toggle()
        })
        $('.longTitle, .shortTitle').toggle()
        $('.fa-chevron-left, .fa-chevron-right').toggle()
    }
  });