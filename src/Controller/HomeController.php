<?php

namespace App\Controller;



use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ApiManager;

class HomeController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/", name="explorer")
     */
    public function HomeDisplay(ApiManager $api)
    {

        //Liste des genres a afficher sur la HomePage pour ajouter un genre il faut l'ajouter aussi dans la tableau moviesArrayHome plus bas
        $genderForHomepage = array("drame" => "18", "comedie" => "35", "histoire" => "36", "scienceFiction" => "878", "horreur" => "27");
        $apiGender = $api->getMovieByGenres($genderForHomepage);
        foreach ($apiGender as $key => $value) {
            $array[$key] = json_decode(($value->getBody())->getContents());
        }

        $specialDataForHomepage = array("popular" => "popular", "upcoming" => "upcoming", "top_rated" => "top_rated");

        $apiSpecialData = $api->getMovieBySpecialData($specialDataForHomepage);

        foreach ($apiSpecialData as $key => $value) {
            $array[$key] = json_decode(($value->getBody())->getContents());
        }
        //Tableau de données utiles pour le twig qui permet l'affichage de la HomePage à modifié en cas d'ajout dun genre ou d'une specialData

        $moviesArraysHome = array(
            array('Films populaires', 'popular', $array['popular']),
            array('Prochaines sorties', 'upcoming', $array['upcoming']),
            array('Films les mieux notés', 'top_rated', $array['top_rated']),
            array('Drame', '18', $array['drame'], true),
            array('Comedie', '35', $array['comedie'], true),
            array('Horreur', '27', $array['horreur'], true),
            array('Science-Fiction', '878', $array['scienceFiction'], true),
            array('Histoire', '36', $array['histoire'], true)
        );
        return $this->render('home/home.html.twig', [
            'moviesArraysHome'          => $moviesArraysHome

        ]);
    }

    /**
     * Affiche tous les films en fonction d'une donnée proposé par l'api 
     * 
     *@Route ("/specialDisplay/{data}", name="specialDisplay")
     */

    public function specialDisplay(ApiManager $api, $data)
    {
        $movieBySpecialData = $api->getMovieBySpecialData($data);
        return $this->render('home/moviesList.html.twig', [
            'movies' => $movieBySpecialData,
        ]);
    }

    /**
     * Envoie vers une page dedié une page de film d'une categorie spécifique
     * @Route("/genre/{id}/{name}", name="genre")
     */
    public function movieByGender(ApiManager $api, $id)
    {
        $movieByGenre = $api->getMovieByGenres($id);

        return $this->render('home/moviesList.html.twig', [
            'movies' => $movieByGenre,
        ]);
    }

    /**
     * Affiche les informations et les details d'un films en particulier sur une page ( movie info )
     * @Route("/film/{title}/{id}", name="movie")
     */
    public function showMovie(ApiManager $api, $id)
    {

        $results = $api->getOneMovieById ($id);
        $movie = json_decode(($results['movie']->getBody())->getContents(), true);
        $credits = json_decode(($results['credits']->getBody())->getContents(), true);

        foreach ($credits['crew'] as $crew) {

            if ($crew['job'] == 'Director') {
                $directors[] =  $crew['name'];
            }
        }
        //Change l'heure perçu en minute en heure
        $runtime = $movie['runtime'];
        $min = $movie['runtime'] % 60;
        $hour = ($runtime - $min) / 60;
        $runtime = $hour . 'h' . $min;

        return $this->render('home/movieInfo.html.twig', [
            'movie'     => $movie,
            'directors' => $directors,
            'credits'   => $credits,
            'runtime'   => $runtime

        ]);
    }

    
}
