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


        $moviesArraysHome = array (
            array ('Films populaires','popular',$popular),
            array ('Prochaines sorties','upcoming',$upcoming),
            array ('Films les mieux notés','top_rated',$top_rated),
            array ('Drame','18',$drame,true),
            array ('Comedie','35',$comedie,true),
            array ('Horreur','27',$horreur,true),
            array ('Science-Fiction','878',$scienceFiction,true),
            array ('Histoire','36',$histoire,true)
        );
         return $this->render('api/home.html.twig', [
             'moviesArraysHome'          => $moviesArraysHome

         ]);
        
    }
    /**
     * Affiche tous les films en fonction d'une donnée proposé par l'api
     *@Route ("/specialDisplay/{data}", name="specialDisplay")
     */

    public function specialDisplay($data) {

        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/'.$data.'?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1');
        
        $movies = json_decode(($response->getBody())->getContents());
        
        return $this->render('api/moviesList.html.twig', [
            'movies' => $movies,
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
        $promises = [
            'movie'     => $client->getAsync('/3/movie/'.$id.'?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR'),
            'credits'   =>$client->getAsync('/3/movie/'.$id.'/credits?api_key=5339f946394a0136198c633aa468ac5b')

        ];
        $results = Promise\unwrap($promises);
        $movie = json_decode(($results['movie']->getBody())->getContents(), true);
        $credits = json_decode(($results['credits']->getBody())->getContents(), true);

        foreach ($credits['crew'] as $crew){

                if ($crew['job'] == 'Director') {
                    $directors[] =  $crew['name'];
                }

        }
        //Change l'heure perçu en minute en heure
        $runtime = $movie['runtime'];
        $min = $movie['runtime']%60; 
        $hour = ($runtime - $min )/60;
        $runtime = $hour .'h'.$min;

        return $this->render('api/movieInfo.html.twig', [
            'movie'     => $movie,
            'directors' =>$directors,
            'credits'   =>$credits, 
            'runtime'   =>$runtime

        ]);
    
    }   
}

