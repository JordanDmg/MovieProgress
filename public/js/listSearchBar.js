
$("#ListSearchBAr").keyup(function (data) {                     
  search = document.getElementById("ListSearchBAr").value
  film = [];
  $("#affichage_results").html('')
  axios
    .get(
      "https://api.themoviedb.org/3/search/movie?page=1&language=fr-FR&api_key=5339f946394a0136198c633aa468ac5b&query=" +
      search
    )

    .then((response) => {
      const films = response.data.results


      films.forEach(element => {
        state = 'btn-primary'

        $(".inId").each(function () {

          if ($(this).text() == element.id) {
            state = "btn-success"
          }
        })
        $("#affichage_results").append(
          `
            <div  style="max-width:154px; height:540px; padding-left:25px">
                       <img
                         src="http://image.tmdb.org/t/p/w154/`+ element.poster_path + `"
                         alt="poster" class="`+ element.poster_path + `"
                         style="width:147px;"
                       >
                       <div class="card-body">
                         <h5 class="card-title">`+ element.title + `</h5>
                         <p class="card-text">`+ element.release_date + `</p>
                         <p hidden class="card-id" >`+ element.id + `</p> 
                         <a href="#"  class="addMovie btn `+ state + `">Ajouter a la liste</a>
                         </div>
            </div>`

        );

      });
      
      $(".addMovie").click(function (data) {
        event.preventDefault()
        title = $(this).siblings(".card-title").text()
        movieId = $(this).siblings(".card-id").text()
        posterPath = $(this).parent().siblings().attr('class')
        listId = $(".listId").text()
        alreadyIn = false

        $(".inId").each(function () {

          if ($(this).text() == movieId) {
              $(this).parent().parent().remove()
            alreadyIn = true
          }
        })
        movieId
        if (alreadyIn === true) {
          axios.put('/profil/removeMovie/' + listId + '/' + movieId )
            .then(response => {
              $(this).toggleClass("btn-success btn-primary")
              console.log($(p).text(movieId))

            }).catch(error => {
              console.log(error.response)
            })
          
        } else {
          axios.put('/profil/addMovie/' + listId + '/' + movieId )
            .then(response => {
              console.log(response.data)
              $(this).toggleClass("btn-primary btn-success")
              $(".movieInList").prepend(`<div  style="max-width:150px; margin-left:15px; border: 1px solid black; min-height:384px;">
                                          <img src="http://image.tmdb.org/t/p/w154/`+ posterPath +`" alt="moviePicture" style="width:147px;">
                                          <div class="card-body">
                                            <h5 class="card-title">`+ title +`</h5> 
                                            <p hidden class="inId" >`+ movieId +`</p> 
                                            </div>
                                        </div>`)

            }).catch(error => {
              console.log(error.response)
            })


        }

      })
    })

});

$(".removeMovie").click(function (data) {
  var listId = $(".listId").text()
  var movieId = $(this).siblings(".inId").text()
  var cardMovie = $(this).parent().parent()
  axios.put('/profil/removeMovie/' + listId + '/' + movieId )
  .then(response => {
   cardMovie.remove()

  }).catch(error => {
    console.log(error.response)
  })
})

