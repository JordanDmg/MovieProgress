<?php

namespace App\Controller;


use App\Form\ListType;
use GuzzleHttp\Client;
use App\Entity\Listing;
use GuzzleHttp\Promise;
use App\Entity\MovieView;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use App\Service\ApiManager;

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
    public function testApi()
    {
        
        
        
        // $moviesArraysHome = array("drame" => "18", "comedie" => "35") ;
        // var_dump($api->getMovieByGenres($moviesArraysHome));
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


        $moviesArraysHome = array(
            array('Films populaires', 'popular', $popular),
            array('Prochaines sorties', 'upcoming', $upcoming),
            array('Films les mieux notés', 'top_rated', $top_rated),
            array('Drame', '18', $drame, true),
            array('Comedie', '35', $comedie, true),
            array('Horreur', '27', $horreur, true),
            array('Science-Fiction', '878', $scienceFiction, true),
            array('Histoire', '36', $histoire, true)
        );
        return $this->render('api/home.html.twig', [
            'moviesArraysHome'          => $moviesArraysHome

        ]);
    }
    /**
     * Affiche tous les films en fonction d'une donnée proposé par l'api 
     * 
     *@Route ("/specialDisplay/{data}", name="specialDisplay")
     */

    public function specialDisplay($data)
    {

        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/' . $data . '?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&page=1');

        $movies = json_decode(($response->getBody())->getContents());

        return $this->render('api/moviesList.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Envoie vers une page dedié une page de film d'une categorie spécifique
     * @Route("/genre/{id}/{name}", name="genre")
     */
    public function movieByGender($idGenre)
    {
        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $response = $client->request('GET', '/3/discover/movie?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR&sort_by=popularity.desc&include_video=false&page=1&with_genres=' . $idGenre);

        $top_rated = json_decode(($response->getBody())->getContents());

        return $this->render('api/moviesList.html.twig', [
            'movies' => $top_rated,
        ]);
    }

    /**
     * Affiche les informations et les details d'un films en particulier sur une page ( movie info )
     * @Route("/film/{title}/{id}", name="movie")
     */
    public function showMovie($id)
    {
        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        $promises = [
            'movie'     => $client->getAsync('/3/movie/' . $id . '?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR'),
            'credits'   => $client->getAsync('/3/movie/' . $id . '/credits?api_key=5339f946394a0136198c633aa468ac5b')

        ];
        $results = Promise\unwrap($promises);
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

        return $this->render('api/movieInfo.html.twig', [
            'movie'     => $movie,
            'directors' => $directors,
            'credits'   => $credits,
            'runtime'   => $runtime

        ]);
    }

    // public function tmdbIdsToMoviesData($tmdbIds)
    // {

    //     $moviesArray = array();
    //     foreach ($tmdbIds as $tmdbId) {

    //         $client = new Client();
    //         $client = $this->get('eight_points_guzzle.client.api_tmdb');
    //         $response = $client->request('GET', '/3/movie/' . $tmdbId . '?api_key=5339f946394a0136198c633aa468ac5b&language=en-US');

    //         $movie_data = json_decode(($response->getBody())->getContents());

    //         $moviesArray = $movie_data;
    //     }
    //     var_dump($moviesArray);
    // }

    /**
     * Fonction d'affichage du menu profil/Films vu 
     * Emplacement temporaire pour l'instant il est ici parcequ'ils fonctionnent avec l'api et je n'ai pas trouvé de solution
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $userMovies = $em->getRepository(MovieView::class)->findMovieByUserId(
            $userId
        );
        $moviesArray = array();
        $promises = array();
        $client = new Client();
        $client = $this->get('eight_points_guzzle.client.api_tmdb');
        foreach ($userMovies as $tmdbId) {
            $promises[$tmdbId['id_tmdb']] = $client->getAsync('/3/movie/' . $tmdbId['id_tmdb'] . '?api_key=5339f946394a0136198c633aa468ac5b&language=fr-FR');
        }

        $results = Promise\unwrap($promises);

        foreach ($results as $result) {
            $moviesArray[] = json_decode(($result->getBody())->getContents(), true);
        }

        return $this->render('profil/profil.html.twig', [
            'controller_name'   => 'Profil',
            'user_movies'        => $moviesArray
        ]);
    }

    /**
     * Fonction d'affichage du menu profil/MesListes
     * Emplacement temporaire pour l'instant il est ici parcequ'ils fonctionnent avec l'api et je n'ai pas trouvé de solution
     * @Route("/profil/MesListes", name="profil_liste")
     */
    public function profilListes(Request $request, ObjectManager $manager, UserInterface $user) {
        $listing = new Listing();
       
        
        $form = $this->createForm(ListType::class, $listing);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $listing->setAuthorId($user->getId());
            $manager->persist($listing);
            $manager->flush();

            return $this->redirectToRoute('modifyList', [
                'id' => $listing->getId()
                ]);
            
        }

        return $this->render('profil/profilListe.html.twig', [
                'formListing' => $form->createView(),
        ]);
    }


}
