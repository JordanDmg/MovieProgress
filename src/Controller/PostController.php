<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Comment;
use App\Entity\Listing;
use App\Entity\MovieView;
use App\Service\ApiManager;
use App\Repository\MovieViewRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\MovieToWatch;

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
     * @Route("view/{apiId}", name="view")
     */
    public function view($apiId, ApiManager $api) 
    {

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $apiId)
        );

        if($movie === null){
            $results = $api->getOneMovieByIdWithFullData($apiId);
            $movieData = json_decode(($results['movie']->getBody())->getContents(), true);
            $credits = json_decode(($results['credits']->getBody())->getContents(), true);

            $movie = new Movie();
            $movie->setIdTMDB($apiId);
            $movie->setName($movieData['title']);
            $movie->setPosterPath($movieData['poster_path']);
            $movie->setRuntime($movieData['runtime']);
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
     * Permet d'ajouter un film à une liste des films à voir
     * @Route("toWatch/{apiId}", name="toWatch")
     */
    public function toWatch($apiId, ApiManager $api) 
    {

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $apiId)
        );

        if($movie === null){
            $results = $api->getOneMovieByIdWithFullData($apiId);
            $movieData = json_decode(($results['movie']->getBody())->getContents(), true);
            $credits = json_decode(($results['credits']->getBody())->getContents(), true);

            $movie = new Movie();
            $movie->setIdTMDB($apiId);
            $movie->setName($movieData['title']);
            $movie->setPosterPath($movieData['poster_path']);
            $movie->setRuntime($movieData['runtime']);
            $em->persist($movie);
        }
        $toWatch = $em->getRepository(MovieToWatch::class)->findOneByUserAndMovie($user->getId(), $movie->getId());

        if($toWatch === null){
            $toWatch = new MovieToWatch();
            $toWatch->setMovie($movie);
            $toWatch->setUser($user);
            $em->persist($toWatch);
            $return = "vu !";
        }
        else{
            $em->remove($toWatch);
            $return = "pas vu !";
        }
        $em->flush();

        return new JsonResponse($return);
    }

    /**
     * Permet d'ajouter un film à une liste de films vus
     * @Route("rate/{apiId}/{rate}", name="rate")
     */
    public function rate($apiId, $rate, ApiManager $api)
    {

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        //Verifie si le film est deja present dans la base de données
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => intval($apiId))
        );
        //S'il ny est pas l'ajoute

        if($movie === null){

            $results = $api->getOneMovieByIdWithFullData($apiId);
            $movieData = json_decode(($results['movie']->getBody())->getContents(), true);
            $credits = json_decode(($results['credits']->getBody())->getContents(), true);

            $movie = new Movie();
            $movie->setIdTMDB($apiId);
            $movie->setName($movieData['title']);
            $movie->setPosterPath($movieData['poster_path']);
            $movie->setRuntime($movieData['runtime']);
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
     * Permet de supprimer une note
     * @Route("/removeRate/{apiId}", name="removeRate")
     */
    public function removeRate($apiId){
        $user = $this->getUser();
        

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => intval($apiId))
        );
        
        $rating = $em->getRepository(MovieView::class)->findOneByUserAndMovie($user->getId(), $movie->getId());

            $rating->setRate(NULL);

            $em->persist($rating);
            $return = "note supprimé";
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
        $return = "Liste ajouté aux favories";
        return new JsonResponse($return);

    }

    /**
     * Permet de retirer une listes de ses listes favorites 
     * @Route("listes/unfollowList/{id}", name="unfollowList")
     * 
     */
    public function unfollowList (Listing $list){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user->removeListing($list);

        $em->persist($user);
        $em->flush();
        $return = "Liste supprimé des favories";
        return new JsonResponse($return);
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
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setContent($content);
        $comment->setCreatedAt($date);
        $comment->setMovie($movie);
        
        $em->persist($comment);
        $em->flush();
        
        return new JsonResponse($comment->getId());
    }

    /**
     * Permet la suppression d'un commentaire
     * @Route("film/deletecomment/{id}", name="deleteComment")
     */
    public function deleteComment($id) {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->findOneBy(   //Recuparation de l'entité film s'il existe
            array('id' => $id)
        );

        $em->remove($comment);
        $em->flush();

        return new JsonResponse('');
        

    }
}