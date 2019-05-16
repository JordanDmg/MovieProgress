<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Comment;
use App\Entity\Listing;
use App\Entity\MovieView;
use App\Repository\MovieViewRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("view/{title}/{apiId}/{posterPath}", name="view")
     */
    public function view($title, $apiId, $posterPath)
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
            $movie->setPosterPath($posterPath);
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
     * @Route("rate/{apiId}/{rate}/{title}/{posterPath}", name="rate")
     */
    public function rate($apiId, $rate, $title, $posterPath)
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
            $movie->setPosterPath($posterPath);
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

     /**
     * Permet d'ajouter une list a ses listes favorites
     * @Route("listes/addliste/{id}", name="addList")
     */
    public function addList (Listing $list) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        dump($list);
        $user->addListing($list);
        $em->persist($user);
        $em->flush();

        

        return $this->render('list/test.html.twig');

    }

    /**
     * Permet d'ajouter un commentaire a un film 
     * @Route("film/addcomment/{id}/{content}", name="addComment")
     */
    public function addComment($id, $content, UserInterface $user) {  

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository(Movie::class)->findOneBy(   //Recuparation de l'entité film s'il existe
            array('idTMDB' => $id)
        );
        
        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTime());
        $comment->setMovie($movie);
        
        $em->persist($comment);
        $em->flush();

        return new JsonResponse('salut');
    }
}