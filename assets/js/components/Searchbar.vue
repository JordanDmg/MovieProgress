<template>
  <div>
    <div>
      <input placeholder="Search..." id="test" v-model="search" @keyup="autoSearch" >
    </div>
    <div class="spinner-border m-5" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>

    <div class="row">
      <div v-for="film in films" class="card" style="width: 18rem;">
        <img
          :src="'http://image.tmdb.org/t/p/w154/' + film.poster_path"
          alt="terrible"
          width="100px"
        >
        <div class="card-body">
          <h5 class="card-title">{{ film.title }}</h5>
          <p class="card-text">{{ film.release_date }}</p>
          <a href="#" v-on:click="addMovie(film.id, film.title, $event)" class="btn btn-primary" >Ajouter a la liste</a>
        </div>
      </div>
    </div>
  </div>


</template>
<script>
export default {
  data() {
    return {
      search: "",
      films: [],
      loading: false,
    };
  },
  props: ['idlist'],
  methods: {
    autoSearch() {
      if (this.search.length >= 3) {
        // this.loading = true
        this.$axios
          .get(
            "https://api.themoviedb.org/3/search/movie?page=1&language=fr-FR&api_key=5339f946394a0136198c633aa468ac5b&query=" +
              this.search
          )
          .then(
            response => (
              (this.films = response.data.results), console.log(response)
              
              )
          );
      }
    },
    addMovie(id, title, event) {
        this.$axios.put('/profil/addMovie/' + this.idlist +'/'+ id +'/'+ title
        )
        .then(response => {
          console.log(response.data)
            if (response.data ==="vu !"){
              event.target.className += " btn-success"
            }else{
               event.target.className="btn btn-primary"
            }
        })
    }
    }
  };

</script>