<?php

namespace App\Controller;
use App\Entity\Movie;
use App\Repository\MovieViewRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\MovieView;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="rienpourlinstant")
     */
    public function index(MovieViewRepository $repo, $id, $title)
    {
        return $this->render('api/home.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    }
    /**
     * Permet d'ajouter un film à une liste de films vus
     * @Route("view/{title}/{apiId}", name="view")
     */
    public function view($title, $apiId)
    {

        $title = \urldecode($title);

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $apiId)
        );

        if($movie === null){
            $movie = new Movie();
            $movie->setIdTMDB($apiId);
            $movie->setName($title);
            $em->persist($movie);
        }
        $view = $em->getRepository(MovieView::class)->findOneByUserAndMovie($user->getId(), $movie->getId());

        if($view === null){
            $view = new MovieView();
            $view->setMovie($movie);
            $view->setUser($user);
            $em->persist($view);
            $return = "vu !";
        }
        else{
            $em->remove($view);
            $return = "pas vu !";
        }
        $em->flush();

        return new JsonResponse($return);
    }

    /**
     * Permet d'ajouter un film à une liste de films vus
     * @Route("rate/{apiId}/{rate}/{title}", name="rate")
     */
    public function rate($apiId, $rate, $title)
    {

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        //Verifie si le film est deja present dans la base de données
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => intval($apiId))
        );
        //S'il ny est pas l'ajoute
        if($movie === null){
            $movie = new Movie();
            $movie->setIdTMDB($apiId);
            $movie->setName($title);
            $em->persist($movie);
        }
        $rating = $em->getRepository(MovieView::class)->findOneByUserAndMovie($user->getId(), $movie->getId());

        if($rating === null){
            $rating = new MovieView();
            $rating->setMovie($movie);
            $rating->setUser($user);
            $rating->setRate($rate);
            $em->persist($rating);
            $return = "vu !";
        }
        else{
            $rating->setRate($rate);
            $em->persist($rating);
            $return = "pas vu !";
        }
        $em->flush();

        return new JsonResponse($return);
    }
}