<?php

namespace App\Controller;


use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/home.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/", name="explorer")
     */
    public function testApi(){
      

        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');

        $promises = [
            'popular' => $client->getAsync('/3/movie/popular?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1'),
            'upcoming'   => $client->getAsync('/3/movie/upcoming?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1&region=FR'),
            'top_rated'  => $client->getAsync('/3/movie/top_rated?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1'),
            'histoire'  => $client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=36'),
            'comedie'  => $client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=35'),
            'drame'  => $client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=18'),
            'scienceFiction'  => $client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=878'),
            'horreur'  => $client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=27'),
        ];
        $results = Promise\unwrap($promises);
        $popular = json_decode(($results['popular']->getBody())->getContents());
        $upcoming = json_decode(($results['upcoming']->getBody())->getContents());
        $top_rated = json_decode(($results['top_rated']->getBody())->getContents());
        $drame = json_decode(($results['drame']->getBody())->getContents());
        $comedie = json_decode(($results['comedie']->getBody())->getContents());
        $horreur = json_decode(($results['horreur']->getBody())->getContents());
        $scienceFiction = json_decode(($results['scienceFiction']->getBody())->getContents());
        $histoire = json_decode(($results['histoire']->getBody())->getContents());

        
        
         return $this->render('api/home.html.twig', [
             'controller_name' => 'ApiController',
             'popular'          =>  $popular,
             'upcoming'         =>  $upcoming,
             'top_rated'        =>  $top_rated,
             'drame'            =>  $drame,
             'comedie'          =>  $comedie,
             'horreur'          =>  $horreur,
             'scienceFiction'   =>  $scienceFiction,
             'histoire'         =>  $histoire,
         ]);
        
    }

    /**
     * @Route("/films-populaires", name="popular_movie")
     */
    public function popularMovies() {

        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/popular?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1');
        
        $popular_movies = json_decode(($response->getBody())->getContents());
        
        return $this->render('api/moviesList.html.twig', [
            'movies' => $popular_movies,
        ]);
    }
    /**
     * @Route("/films-a-venir", name="upcoming")
     */
    public function upcoming() {

        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/upcoming?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1&region=FR');
        
        $upcoming = json_decode(($response->getBody())->getContents());
        
        return $this->render('api/moviesList.html.twig', [
            'movies' => $upcoming,
        ]);
    }
    /**
     * @Route("/top-rated", name="top_rated")
     */
    public function topRatedMovies() {
        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/top_rated?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1');
        
        $top_rated = json_decode(($response->getBody())->getContents());
        
        return $this->render('api/moviesList.html.twig', [
            'movies' => $top_rated,
        ]);
    }

    /**
     * @Route("/genre/{id}/{name}", name="genre")
     */
    public function movieByGender($id) {
        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', '/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres='.$id);
        
        $top_rated = json_decode(($response->getBody())->getContents());
        
        return $this->render('api/moviesList.html.twig', [
            'movies' => $top_rated,
        ]);
    }

    /**
     * @Route("/film/{title}/{id}", name="movie")
     */
    public function showMovie ($id) {
        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/166428?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR');

        $movie = json_decode(($response->getBody())->getContents());

        return $this->render('api/movieInfo.html.twig', [
            'movie' => $movie,
        ]);
    
    }   

 
}

