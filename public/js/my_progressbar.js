

document.addEventListener('DOMContentLoaded', function() {
    var movieView = $('#movieViews').data('count');
    var movieTotal = $('#movieTotal').data('total');

    var pourcent = movieView / movieTotal ;

    console.log(pourcent)
    var bar = new ProgressBar.Line('#progressbar', {
        strokeWidth: 4,
        easing: 'easeInOut',
        duration: 1400,
        color: '#f2cd95',
        // trailColor: '#eee',
        // trailWidth: 1,
        svgStyle: {width: '100%', border: '1px solid white'},
        text: {
          style: {
            // Text color.
            // Default: same as stroke color (options.color)
            color: '#999',
            // position: 'absolute',
            padding: 0,
            margin: 0,
            transform: null
          },
          autoStyleContainer: false
        },
        from: {color: '#f2cd95'},
        to: {color: '#EDB867'},
        step: (state, bar) => {
          bar.setText(Math.round(bar.value() * 100) + ' %');
          bar.path.setAttribute('stroke', state.color);
        }
      });

    bar.animate(pourcent);  // Value from 0.0 to 1.0

    $(".watch-btn").click(function (e) {
        var state = $(this).find('i').attr("class")
    
        // var movieView = $('#movieViews').data('count');
        // var movieTotal = $('#movieTotal').data('total');
    
        if (state === 'seenIcone notSeen far fa-eye fa-lg') {
    
            movieView = movieView - 1 ;
            $('#movieViews').html(movieView);
            $('#movieViews').data('count', movieView);
            var pourcent = movieView / movieTotal ;
    
        } else {
           
            movieView = movieView + 1;
            $('#movieViews').html(movieView);
            $('#movieViews').data('count', movieView);
            var pourcent = movieView / movieTotal ;
            
        }
        bar.animate(pourcent);  // Value from 0.0 to 1.0
    
    });

    

});



// $( document ).ready(function() {

//     // Assuming we have an empty <div id="container"></div> in
//     // HTML
//     console.log(movieView);
//     var bar = new ProgressBar.Line('#progressbar', {easing: 'easeInOut'});
//     bar.animate(1);  // Value from 0.0 to 1.0

// });
