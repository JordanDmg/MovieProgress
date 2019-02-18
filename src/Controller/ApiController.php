<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Tmdb\ApiToken;

class ApiController extends Controller
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/test", name="testapi")
     */
    public function testApi(){
        $client = $this->get('tmdb.client');
        dump($client);
        $movie = $this->get('tmdb.movie_repository')->load(13);
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'movie'          =>  $movie
        ]);
    }
    
}
