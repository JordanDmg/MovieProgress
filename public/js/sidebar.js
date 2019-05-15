$(document).ready(function () {                 //Fonction servant à deployer ou minimiser la side bar 

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
        $('#affichage').toggleClass('active');    
        $('li a span').each(function () {
            $(this).toggle()
        })
        $('input').toggle()
        $('.longTitle, .shortTitle').toggle()
        $('.fa-chevron-left, .fa-chevron-right').toggle()
        
       
    });

});
$(document).ready(function () {

    $('#searchCollapse').on('click', function () {      //Fonction qui ne fait que deployer la side bar lors ce qu'on clique sur chercher
        input = $(this).children('input')
        
        if($('#sidebar').attr('class').includes('active')){         //Permet de ne pas rabattre la bar en voulant chercher un film
            $('#sidebar').toggleClass('active');
            $('#content').toggleClass('active');
            
            
    
            $('li a span').each(function () {
                $(this).toggle()
            })
            $('input').toggle()
            $('.longTitle, .shortTitle').toggle()
            $('.fa-chevron-left, .fa-chevron-right').toggle()
            input.focus()
            input.select()
        }
       
       
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