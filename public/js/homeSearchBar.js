
$("#homeSearch").keyup(function (data) {

     search = document.getElementById("homeSearch").value
     $("#test").show();
     i = 0;
     film = [];
    $("#test").html('')
    if (!search){
        

    }else {
        
        


        axios
        .get(
          "https://api.themoviedb.org/3/search/movie?page=1&language=fr-FR&api_key=5339f946394a0136198c633aa468ac5b&query=" +
          search
        )
    
        .then((response) => {
          const films = response.data.results
            console.log(films);
          
          films.forEach(element => {
            state = 'btn-primary'
            i++; 
            if ( i <= 5){
            $(".movieIn").each(function () {
    
              if ($(this).text() == element.id) {
                state = "btn-success"
              }
            })
            $("#test").append(
            `<div class="col">
              <a href="`+ Routing.generate('movie', {'id':element.id }) +`" class="mousehover over_a">
                <div class="card flex-md-row mb-3 shadow-sm" style="background-color: #272b30">
                 
                        <img alt="" class="bd-placeholder-img " style="width:48px;"src="https://image.tmdb.org/t/p/w154/`+ element.poster_path + `">
                       
                    
                    <div  style="color:#FFF;">
                        <h8 class="card-text">`+ element.title+`</h8></BR>
                        <h8 class="card-text">`+ element.release_date.substr(0,4) +`</h8>       
                    </div>                
                </div>
              </a>
            </div>  `
    
            );
          }   
          });

          $( ".mousehover" ).hover(
            function() {
              $( this ).addClass( "hover" );
             $(this).children('i').show()
            }, function() {
              $( this ).removeClass( "hover" );
             $(this).children('i').hide()
        
            }
          );

        })
    }


  
  });





//   $("#test").keyup(function (data) {
//     search = document.getElementById("test").value
//     film = [];
//    $("#affichage").html('')
//    if (!search){
//        $("#content").show()

//    }else {
//        $("#content").hide()
//        axios
//        .get(
//          "https://api.themoviedb.org/3/search/movie?page=1&language=fr-FR&api_key=5339f946394a0136198c633aa468ac5b&query=" +
//          search
//        )
   
//        .then((response) => {
//          const films = response.data.results
   
   
//          films.forEach(element => {
//            state = 'btn-primary'
   
//            $(".movieIn").each(function () {
   
//              if ($(this).text() == element.id) {
//                state = "btn-success"
//              }
//            })
//            $("#affichage").append(
//              `<div class="content">
//                <div style="width: 18rem;">
//                           <img
//                             src="http://image.tmdb.org/t/p/w154/`+ element.poster_path + `"
//                             alt="terrible" class="`+ element.poster_path + `"
                            
//                           >
//                           <div class="card-body">
//                             <h5 class="card-title">`+ element.title + `</h5>
//                             <p class="card-text">`+ element.release_date + `</p>
//                             <p hidden class="card-id" >`+ element.id + `</p> 
//                             <a href="#"   class="addMovie btn `+ state + `">Ajouter a la liste</a>
//                             </div>
//                </div>`
   
//            );
   
//          });
         
//          $(".addMovie").click(function (data) {
//            event.preventDefault()
//            title = $(this).siblings(".card-title").text()
//            movieId = $(this).siblings(".card-id").text()
//            posterPath = $(this).parent().siblings().attr('class')
//            listId = $(".listId").text()
//            alreadyIn = false
   
//            $(".movieIn").each(function () {
   
//              if ($(this).text() == movieId) {
//                $(this).remove()
//                alreadyIn = true
//              }
//            })
//            movieId
//            console.log(alreadyIn)
//            if (alreadyIn === true) {
//              axios.put('/profil/removeMovie/' + listId + '/' + movieId )
//                .then(response => {
//                  console.log(response.data)
//                  $(this).toggleClass("btn-success btn-primary")
   
//                }).catch(error => {
//                  console.log(error.response)
//                })
             
//            } else {
//              axios.put('/profil/addMovie/' + listId + '/' + movieId + '/' + title  + posterPath)
//                .then(response => {
//                  console.log(response.data)
//                  $(this).toggleClass("btn-primary btn-success")
//                  $(".movieInList").append(`<p class="movieIn col-6">` + movieId + `</p>`)
   
//                }).catch(error => {
//                  console.log(error.response)
//                })
   
   
//            }
   
   
   
   
   
   
   
//          })
//        })
//    }
//  });