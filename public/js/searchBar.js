// import Axios from "axios";

$("#test").keyup(function (data) {
    search = document.getElementById("test").value
    film = [];
    $( "#affichage" ).html('')
    axios
        .get(
            "https://api.themoviedb.org/3/search/movie?page=1&language=fr-FR&api_key=5339f946394a0136198c633aa468ac5b&query=" +
            search
        )

        .then((response) => {
            const films = response.data.results
            films.forEach(element => {
                console.log(element)
                 $( "#affichage" ).append( 
                    `<divclass="card" style="width: 18rem;">
                       <img
                         src="http://image.tmdb.org/t/p/w154/` + element.poster_path + `"
                         alt="terrible"
                         
                       >
                       <div class="card-body">
                         <h5 class="card-title">`+ element.title +`</h5>
                         <p class="card-text">`+ element.release_date +`</p>
                         
                       </div>
                     </div>`
                     );
            });
        })

});