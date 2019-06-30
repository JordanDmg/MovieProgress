<?php

namespace App\Controller;



use App\Entity\Movie;
use App\Form\CommentType;
use App\Service\ApiManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        dump($movieBySpecialData);
        return $this->render('home/moviesList.html.twig', [
            'movies' => $movieBySpecialData,
        ]);
    }

    /**
     * Envoie vers une page  une page de film d'une categorie spécifique
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
     * @Route("/film/{id}", options={"expose"=true}, name="movie")
     */
    public function showMovie( $id, ApiManager $api)
    {   //Recuperation des informations d'un film grace a son apiId en contactant l'api

        $results = $api->getOneMovieByIdWithFullData($id);
        $movie = json_decode(($results['movie']->getBody())->getContents(), true);
        $credits = json_decode(($results['credits']->getBody())->getContents(), true);
        $directors = array();
        $screenplays = array();
        foreach ($credits['crew'] as $crew) {       
            if ($crew['job'] == 'Director') {       //Recuperation des Scenariste pour un affichage avec lien
                $directors[] =  $crew;
            }
            if ($crew['job'] == 'Screenplay') {     //Même chose pour les scenaristes
                $screenplays[] =  $crew;
                
            }
        }
        dump($directors);
        $title = $movie['title'];
        $posterPath = $movie['poster_path'];
        $runtime = $movie['runtime'];
        $em = $this->getDoctrine()->getManager();
        $movieFromDatabase = $em->getRepository(Movie::class)->findOneBy(   //Recuparation de l'entité film s'il existe
            array('idTMDB' => $id)
        );

        if($movieFromDatabase === null){                                                //Créer l'entité film à partir des infos de l'api s'il existe pas 
            $movieFromDatabase = new Movie();
            $movieFromDatabase->setIdTMDB($id);
            $movieFromDatabase->setName($title);
            $movieFromDatabase->setPosterPath($posterPath);
            $movieFromDatabase->setRuntime($runtime);
            $em->persist($movieFromDatabase);
            $em->flush();
        }
        $commentForm = $this->createForm(CommentType::class);
        
        //Change l'heure perçu en minute en heure
        $min = $movie['runtime'] % 60;
        $hour = ($runtime - $min) / 60;
        $runtime = $hour . 'h' . $min;
        return $this->render('home/movieInfo.html.twig', [
            'movie'         => $movie,
            'directors'     => $directors,
            'screenplays'   => $screenplays,
            'credits'       => $credits,
            'runtime'       => $runtime,
            'movieDB'       => $movieFromDatabase,
            'form'          => $commentForm->createView()    

        ]);
    }

    /**
     * Affiche les information d'une personnalité selectionné 
     * @Route("/personnalite/{id}", name="people")
     */
    public function showPeople($id, ApiManager $api) {
        $apiPeople = $api->getPeopleById($id);
        $people_details = json_decode(($apiPeople["details"]->getBody())->getContents());
        $people_movieCredits = json_decode(($apiPeople["movie_credits"]->getBody())->getContents());
        dump($people_movieCredits);
        return $this->render('home/people.html.twig', [
            'people_details'            => $people_details,
            'people_movieCredit'        => $people_movieCredits,

        ]);
    }
}
