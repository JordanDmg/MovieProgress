<?php

namespace App\Controller;
use App\Entity\Movie;
use App\Form\ListType;

use App\Entity\Listing;
use App\Entity\MovieView;
use App\Service\ApiManager;
use App\Entity\MovieToWatch;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ProfilController extends AbstractController
{
    /**
     * Fonction d'affichage du menu profil/Films vu 
     * @Route("/profil", name="profil")
     */
    public function profil(ApiManager $api)
    {
        $userId = $this->getUser()->getId();
        $totalRuntimeFormated= 0;
        $em = $this->getDoctrine()->getManager();
        $userMovies = $em->getRepository(MovieView::class)->findMovieByUserId(
            $userId
        );
        $totalRuntime = 0;
        foreach ($userMovies as $movie) {
            
            $movieData = $em->getRepository(Movie::class)->findOneBy(
                array('idTMDB' => $movie['id_tmdb'])
            );
            $totalRuntime += $movieData->getRuntime();
            $min = $totalRuntime % 60;
            $hour = ($totalRuntime - $min) / 60;
            $totalRuntimeFormated = $hour . 'h' . $min;

        }
        dump($totalRuntime);
        return $this->render('profil/profil.html.twig', [
            'controller_name'   => 'Profil',
            'user_movies'       => $userMovies,
            'totalRuntime'      => $totalRuntimeFormated,
        ]);
    }
    /**
     * Foction d'affichage du menu profil/movieToWatch
     * @Route("/profil/MovieToWatch", name="profil_movieToWatch")
     */
    public function profilMovieToWatch(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $userId = $this->getUser()->getId();
        $totalRuntimeFormated= 0;
        $em = $this->getDoctrine()->getManager();
        $userMovies = $em->getRepository(MovieToWatch::class)->findBy(
            array('user' => $userId)
        );
        $userMovieView = $em->getRepository(MovieView::class)->findMovieByUserId(
            $userId
        );
        $totalRuntime = 0;
        foreach ($userMovieView as $movie) {
            
            $movieData = $em->getRepository(Movie::class)->findOneBy(
                array('idTMDB' => $movie['id_tmdb'])
            );
            $totalRuntime += $movieData->getRuntime();
            $min = $totalRuntime % 60;
            $hour = ($totalRuntime - $min) / 60;
            $totalRuntimeFormated = $hour . 'h' . $min;

        }
        return $this->render('profil/profilMovieToWatch.html.twig', [
            'controller_name'   => 'Profil',
            'user_movies'       => $userMovies,
            'totalRuntime'      => $totalRuntimeFormated,
        ]);
    }

    /**
     * Fonction d'affichage du menu profil/MesListes
     * @Route("/profil/MesListes", name="profil_liste")
     */
    public function profilListes(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $totalRuntimeFormated= 0;
        $listing = new Listing();
        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $userMovies = $em->getRepository(MovieView::class)->findMovieByUserId(
            $userId
        );
        $totalRuntime = 0;
        foreach ($userMovies as $movie) {
            
            $movieData = $em->getRepository(Movie::class)->findOneBy(
                array('idTMDB' => $movie['id_tmdb'])
            );
            $totalRuntime += $movieData->getRuntime();
            $min = $totalRuntime % 60;
            $hour = ($totalRuntime - $min) / 60;
            $totalRuntimeFormated = $hour . 'h' . $min;

        }
        //Creation du formulaire pour le boutton créer une Liste
        $form = $this->createForm(ListType::class, $listing);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $lists = $em->getRepository(Listing::class)->findBy(
            array('authorId' => $userId)
        );
        return $this->render('profil/profilListe.html.twig', [
            'formListing' => $form->createView(),
            'user_lists'    => $lists,
            'totalRuntime'      => $totalRuntimeFormated,

        ]);
    }

    /**
     * Affichage de la page des listes suivie par l'utilisateur
     * @Route("/profil/ListesSuivies", name="followed_list")
     */
    public function followedList(UserInterface $user ){
        $totalRuntimeFormated= 0;
        $em = $this->getDoctrine()->getManager();   
        $listsFollowed = $user->getListings();
        $userMovies = $em->getRepository(MovieView::class)->findMovieByUserId(
            $user->getId()
        );
        $totalRuntime = 0;
        foreach ($userMovies as $movie) {
            
            $movieData = $em->getRepository(Movie::class)->findOneBy(
                array('idTMDB' => $movie['id_tmdb'])
            );
            $totalRuntime += $movieData->getRuntime();
            $min = $totalRuntime % 60;
            $hour = ($totalRuntime - $min) / 60;
            $totalRuntimeFormated = $hour . 'h' . $min;

        }

        return $this->render('profil/profilListsFollowed.html.twig', [
            'controller_name'   => 'Profil',
            'lists_followed'    => $listsFollowed,
            'totalRuntime'      => $totalRuntimeFormated,

           
        ]);
    }
}
