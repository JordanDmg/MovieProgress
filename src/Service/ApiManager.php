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
    public function getMovieByGenres($genres)
    {

        $promises = array();
        if (is_array($genres)) {
            foreach ($genres as $key => $genre) {
                $promises[$key] = $this->_client->getAsync('/3/discover/movie?api_key=' . $this->_apiKey . '&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=' . $genre);
            }
            return Promise\unwrap($promises);
        } else {
            $response = $this->_client->request('GET', '/3/discover/movie?api_key=' . $this->_apiKey . '&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=' . $genres);
            return json_decode(($response->getBody())->getContents());
        }
    }
    //Permet la recuperation depuis l'api de films en fonction d'une ou plusieurs donnée spécial ( top rated, popular ... )
    public function getMovieBySpecialData($specialData)
    {
        $promises = array();
        if (is_array($specialData)) {
            foreach ($specialData as $key => $value) {
                $promises[$key] = $this->_client->getAsync('/3/movie/' . $value . '?api_key=' . $this->_apiKey . '&language=fr-FR&page=1');
            }

            return Promise\unwrap($promises);
        } else {
            $response = $this->_client->request('GET', 'http://api.themoviedb.org/3/movie/' . $specialData . '?api_key=' . $this->_apiKey . '&language=fr-FR&page=1');
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
            'movie_credits' => $this->_client->getAsync('/3/person/' . $id . '/movie_credits?api_key=' . $this->_apiKey . '&language=fr-FR'),

        ];

        return Promise\unwrap($promises);

    }
}
