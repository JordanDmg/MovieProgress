<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;




class ApiManager
{
    private $client;
    private $apiKey;


    function __construct()
    {
        $this->_client = new Client(['base_uri' => 'http://api.themoviedb.org/']);
        $this->_apiKey = "5339f946394a0136198c633aa468ac5b";
    }


    //Permet la recuperation depuis l'api de films en fonction d'un genre ou plusieurs donné
    public function getMovieByGenres($genres, $page)
    {

        $promises = array();
        if (is_array($genres)) {
            foreach ($genres as $key => $genre) {
                $promises[$key] = $this->_client->getAsync('/3/discover/movie?api_key=' . $this->_apiKey . '&language=fr-FR&sort_by=popularity.desc&include_video=false&page='.$page.'&with_genres=' . $genre);
            }
            return Promise\unwrap($promises);
        } else {
            $response = $this->_client->request('GET', '/3/discover/movie?api_key=' . $this->_apiKey . '&language=fr-FR&sort_by=popularity.desc&include_video=false&page='.$page.'&with_genres=' . $genres);
            return json_decode(($response->getBody())->getContents());
        }
    }
        //Permet de recuperer les films tendances cette semaine
        public function getTrendingsMovie($page)
        {
    
            $response = $this->_client->request('GET','/3/trending/movie/week?api_key=' . $this->_apiKey . '&language=fr-FR&page='.$page.'&region=FR');
            return json_decode(($response->getBody())->getContents());    
                
            
        }
    //Permet de recuperer les films acutellement en salle 
    public function getActualsMovies($page)
    {

        $response = $this->_client->request('GET','/3/movie/now_playing?api_key=' . $this->_apiKey . '&language=fr-FR&page='.$page.'&region=FR');
        return json_decode(($response->getBody())->getContents());    
            
        
    }
     //Permet de recuperer les personnalité populaires
     public function getTrendingsPeoples($page)
     {
 
         $response = $this->_client->request('GET','/3/trending/person/day?api_key=' . $this->_apiKey . '&language=fr-FR&page='.$page.'&region=FR');
         return json_decode(($response->getBody())->getContents());    
             
         
     }
    //Permet la recuperation depuis l'api de films en fonction d'une ou plusieurs donnée spécial ( top rated, popular ... )
    public function getMovieBySpecialData($specialData, $page)
    {
        $promises = array();
        if (is_array($specialData)) {
            foreach ($specialData as $key => $value) {
                $promises[$key] = $this->_client->getAsync('/3/movie/' . $value . '?api_key=' . $this->_apiKey . '&language=fr-FR&page='.$page.'&region=FR');
            }

            return Promise\unwrap($promises);
        } else {
            $response = $this->_client->request('GET', 'http://api.themoviedb.org/3/movie/' . $specialData . '?api_key=' . $this->_apiKey . '&language=fr-FR&page='.$page.'');
            return json_decode(($response->getBody())->getContents());
        }
    }
    //Recuperer toute les données d'un film en fonction de sont id tmdb + les details des realisateur, acteur ...
    public function getOneMovieByIdWithFullData($id)
    {

        $promises = [
            'movie'     => $this->_client->getAsync('/3/movie/' . $id . '?api_key=' . $this->_apiKey . '&language=fr-FR'),
            'credits'   => $this->_client->getAsync('/3/movie/' . $id . '/credits?api_key='.$this->_apiKey.'')

        ];

        return Promise\unwrap($promises);
    }
    // //Recuperation de donnée simplifié pour un film ou plusieurs films en fonction de sont id tmdb Inutile pour l'instant
    // public function getOneMovieById ($ids) {
    //     if (is_array($ids)){
    //         foreach ($ids as $id ){
    //             $promises [] = $this->_client->getAsync('/3/movie/' . $id . '?api_key='.$this->_apiKey.'&language=fr-FR');
    //         }
    //         return Promise\unwrap($promises);
    //     }
    // }

    public function getPeopleById($id) {
        $promises = [
            'details'       => $this->_client->getAsync('/3/person/' . $id . '?api_key=' . $this->_apiKey . '&language=fr-FR'),
            'movie_cast' => $this->_client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=release_date.desc&include_adult=false&include_video=false&page=1&with_cast=' . $id . ''),
            'movie_crew' => $this->_client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=release_date.desc&include_adult=false&include_video=false&page=1&with_crew=' . $id . ''),
            'know_for' => $this->_client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_adult=false&include_video=false&page=1&with_cast=' . $id . ''),
            // 'know_for'      
        ];

        return Promise\unwrap($promises);

    }

    // public function getPeopleById($id) {
    //     $promises = [
    //         'details'       => $this->_client->getAsync('/3/person/' . $id . '?api_key=' . $this->_apiKey . '&language=fr-FR'),
    //         'movie_credits' => $this->_client->getAsync('/3/person/' . $id . '/movie_credits?api_key=' . $this->_apiKey . '&language=fr-FR&sort_by=release_date.asc'),
    //         // 'know_for'      
    //     ];

    //     return Promise\unwrap($promises);

    // }

}
