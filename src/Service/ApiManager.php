<?php 

namespace App\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;



class ApiManager
{
    private $params;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }
    
    public function getMovieByGenres($genres)
    {
        $promises = array();
        $client = $this->params->get('eight_points_guzzle.client.api_tmdb');
        // var_dump($client);

        foreach ($genres as $key => $genre){
            $promises[$key] = $this->client->getAsync('/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres='.$genre );
        }

        $results = Promise\unwrap($promises);

        return $results;
    }
}
