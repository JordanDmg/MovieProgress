<template>
<div>
    <div>
        <input type="text" placeholder="Search..." v-model="search" @keyup="autoSearch">
    </div>
    <div class="spinner-border m-5" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div v-for="film in films" >
        {{ film.title }} {{ film.release_date }}
    </div>
</div>

</template>
<script>
export default {
    data() {
        return {
            search: '', 
            films: [], 
            loading: false
        }
    },
    methods: {
        autoSearch () {
            
            if (this.search.length >= 3){
                // this.loading = true
                this.$axios
                .get('https://api.themoviedb.org/3/search/movie?page=1&language=fr-FR&api_key=5339f946394a0136198c633aa468ac5b&query='+this.search)
                .then(response => (
                    this.films = response.data.results,
                    console.log(response)
                    ))
            }
            }
        }
    }
</script>