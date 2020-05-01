<?php

namespace App\Controller;



use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Listing;
use App\Entity\MovieView;
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
        // $genderForHomepage = array("drame" => "18", "comedie" => "35", "histoire" => "36", "scienceFiction" => "878", "horreur" => "27");
        // $apiGender = $api->getMovieByGenres($genderForHomepage, 1);
        // foreach ($apiGender as $key => $value) {
        //     $array[$key] = json_decode(($value->getBody())->getContents());
        // }

        $specialDataForHomepage = array("popular" => "popular", "upcoming" => "upcoming", "top_rated" => "top_rated", "now_playing" => "now_playing");

        $apiSpecialData = $api->getMovieBySpecialData($specialDataForHomepage, 1);
        $apiTrendingsMovie= $api->getTrendingsMovie(1);
        $apiTrendingsPeoples= $api->getTrendingsPeoples(1);
        foreach ($apiSpecialData as $key => $value) {
            $array[$key] = json_decode(($value->getBody())->getContents());
        }
        // //Tableau de données utiles pour le twig qui permet l'affichage de la HomePage à modifié en cas d'ajout dun genre ou d'une specialData

        // $moviesArraysHome = array(
        //     array('Films populaires', 'popular', $array['popular']),
        //     array('Prochaines sorties', 'upcoming', $array['upcoming']),
        //     array('Films les mieux notés', 'top_rated', $array['top_rated']),
        //     array('Drame', '18', $array['drame'], true),
        //     array('Comedie', '35', $array['comedie'], true),
        //     array('Horreur', '27', $array['horreur'], true),
        //     array('Science-Fiction', '878', $array['scienceFiction'], true),
        //     array('Histoire', '36', $array['histoire'], true)
        // );
             // return $this->render('home/home.html.twig', [
        //     'moviesArraysHome'          => $moviesArraysHome

        // ]);
        
         return $this->render('home/home.html.twig', [
            'ActualsMovies'         => $array['now_playing'], 
            'UpcomingMovies'        => $array['upcoming'],
            'TendingsMovies'        => $apiTrendingsMovie,
            'TrendingsPeoples'       => $apiTrendingsPeoples

        ]);
       
    }

    /**
     * Affiche tous les films en fonction d'une donnée proposé par l'api 
     * 
     *@Route ("/specialDisplay/{data}/{page}", name="specialDisplay")
     */

    public function specialDisplay(ApiManager $api, $data, $page=1)
    {
        $movieBySpecialData = $api->getMovieBySpecialData($data, $page);
        $pageType = 'specialDisplay';

        return $this->render('home/moviesList.html.twig', [
            'pageName'  => $data,
            'movies'    => $movieBySpecialData,
            'id'        => $api,
            'pageType'  => $pageType


        ]);
    }

    /**
     * Envoie vers une page  une page de film d'une categorie spécifique
     * @Route("/genre/{id}/{name}/{page}", name="genre")
     */
    public function movieByGender(ApiManager $api, $id, $name, $page=1)
    {

        $movieByGenre = $api->getMovieByGenres($id, $page);
        $pageType = 'gender';
        return $this->render('home/moviesList.html.twig', [
            'pageName'  => $name,
            'movies'    => $movieByGenre,
            'id'   => $id, 
            'pageType'  => $pageType
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
        $moviesRates = $em->getRepository(MovieView::class)->findBy (
            array('movie' => $movieFromDatabase->getId() )
        );
        $i=0;

        if (!empty($moviesRates)){

            $allrate = 0;
            foreach ($moviesRates as $movieRate){
                if (!is_null($movieRate->getRate())){
                $i++;
                $allrate += $movieRate->getRate();
                }
            }
            if($i != 0  ){
            $avgRate = $allrate/$i;

            }
            else {
            $avgRate = "Aucune note";

            }

        }else {
            $avgRate = "Aucune note";
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
            'form'          => $commentForm->createView(),
            'avgRate'       => $avgRate,
            'nbVotant'      => $i   

        ]);
    }
    /**
     * Affiche les information d'un autre utilisateur
     * @Route("/utilisateur/{id}", name="userInfo")
     */
    public function showUser(User $user ){
        $em = $this->getDoctrine()->getManager();
        $listings = $em->getRepository(Listing::class)->findBy(   //Recuparation de l'entité film s'il existe
            array('authorId' => $user->getId())
        );  
        return $this->render('home/userInfo.html.twig',[
            'user'          => $user,
            'listings'      => $listings
        ]);
    }
    /**
     * Affiche les information d'une personnalité selectionné 
     * @Route("/personnalite/{id}", name="people")
     */
    public function showPeople($id, ApiManager $api) {
        $apiPeople = $api->getPeopleById($id);
        $people_details = json_decode(($apiPeople["details"]->getBody())->getContents());
        $people_cast = json_decode(($apiPeople["movie_cast"]->getBody())->getContents());
        $people_crew = json_decode(($apiPeople["movie_crew"]->getBody())->getContents());
        $know_for = json_decode(($apiPeople["know_for"]->getBody())->getContents());
        return $this->render('home/people.html.twig', [
            'people_details'            => $people_details,
            'people_movieCast'        => $people_cast,
            'people_movieCrew'        => $people_crew,
            'know_for'                => $know_for

        ]);
    }
    /**
     * Affiche le casting complet d'un film
    * @Route("/casting/{id}", name="casting")
     */
    public function fullCasting ($id, ApiManager $api){
        $results = $api->getOneMovieByIdWithFullData($id);
        $movie = json_decode(($results['movie']->getBody())->getContents(), true);
        $credits = json_decode(($results['credits']->getBody())->getContents(), true);
        return $this->render('home/fullCasting.html.twig', [
            'movie'     => $movie,
            'credits'   => $credits

        ]);
    }
}
