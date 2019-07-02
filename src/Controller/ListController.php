<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movie;
use App\Form\ListType;
use App\Entity\Listing;
use App\Service\ApiManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListController extends AbstractController
{
    /**
     * @Route("/profil/newList", name="newList")
     */
    public function createList(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $listing = new Listing();
       
        
        $form = $this->createForm(ListType::class, $listing);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $listing->setAuthorId($user->getId());
            $listing->setImgPath("../img/emptyImg.jpg");
            $manager->persist($listing);
            $manager->flush();

            return $this->redirectToRoute('modifyList', [
                'id' => $listing->getId()
                ]);
            
        }
        return $this->redirectToRoute('profil_liste');
    }

    /**
     * @Route("/profil/modificationList/{id}", name="modifyList") 
     */
    public function modifyList(Listing $list)
    {
        $currentUserId = $this->getUser()->getId();
        if ($currentUserId === $list->getAuthorId()){
            return $this->render('list/modifyList.html.twig', [
                'list'    => $list,
                
            ]);
        
        }else return $this->redirectToRoute('profil_liste');
    }

     /**
     * Permet d'afficher les films d'une liste
     * @Route("/liste/{id}", name="readList")
     */
    public function readList (Listing $list)
    {
        $authorId = $list->getAuthorId();
        $em = $this->getDoctrine()->getManager();
        $follow = false;
        $author = $em->getRepository(User::class)->findOneBy(
            array('id'=> $authorId)
        );        

        $authorUsername = $author->getUsername();
        foreach ($list->getUsers() as $user){
            if ($user->getId() == $this->getUser()->getId()) {
                $follow = "oui";
            }
        }

        return $this->render('list/readList.html.twig', [
            'list' => $list,
            'authorUsername'=> $authorUsername,
            'follow'    => $follow
        ]);
    }


    /**
     * @Route("profil/addMovie/{idList}/{id}", name="addMovie")
     */
    public function addMovie($idList, $id, ApiManager $api) {
        
        $em = $this->getDoctrine()->getManager();

        // Recherche dans la bdd l'id TMDB reçu, et l'ajoute s'il est completement 
        // inconnu de la base de données 
        
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $id)
        );
        if($movie === null){
            $results = $api->getOneMovieByIdWithFullData($id);
            $movieData = json_decode(($results['movie']->getBody())->getContents(), true);
            $credits = json_decode(($results['credits']->getBody())->getContents(), true);

            $movie = new Movie();
            $movie->setIdTMDB($id);
            $movie->setName($movieData['title']);
            $movie->setPosterPath($movieData['poster_path']);
            $movie->setRuntime($movieData['runtime']);
            $em->persist($movie);
        }
        
        $list = $em->getRepository(Listing::class)->findOneBy(
            array('id'  => $idList)
        );
        $list->addMovie($movie);
        
        $list->setImgPath($movie->getPosterPath());
            $em->persist($list);
            $return = "vu !";

        $em->flush();
        
        return new JsonResponse($return, 200);
     }

     /**
     * @Route("/profil/removeMovie/{idList}/{idMovie}", name="removeMovie")
     */
    public function removeMovie($idList, $idMovie ) {
        
        $em = $this->getDoctrine()->getManager();

        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $idMovie)
        );
        
        $list = $em->getRepository(Listing::class)->findOneBy(
            array('id'  => $idList)
        );

        $list->removeMovie($movie);
            $em->persist($list);
            $return = "delete !";

        $em->flush();
        return new JsonResponse($return, 200);
     }
     
    /**
     * Fonction permettant de verifier si un film appartient deja a une liste
     * @Route("profil/tcheking/{idList}/{id}", name="alreadyIn")
     * 
     */
     public function alreadyIn( $idList, $id) {

            $em = $this->getDoctrine()->getManager();
            $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $id)
            );
            $list = $em->getRepository(Listing::class)->findOneBy(
                array('id'  => $idList)
            );
            $testAlreadyIn = $list->getMovies()->contains($movie);

            if ($testAlreadyIn === false){
                $return = "not in";
            }else{
                $return = "in";
            }

            return new JsonResponse($return, 200);
     }


     /**
      * Affichage du menu Listes sur la pages explorer 
      * @Route("/listes", name="homeList")
      */
     public function homeList() {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Listing::class)->findAll();
        return $this->render('list/homeListes.html.twig', [
            'lists' => $list,
    ]);


     }

     /**
      * Suppression d'une liste
      * @Route("/profil/removeList/{id}", name="removeList")
      */
      public function removeList(Listing $list) {


        $currentUserId = $this->getUser()->getId();
        if ($currentUserId === $list->getAuthorId()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($list);
            $em->flush();
    
            return $this->redirectToRoute('profil_liste');
        
        }else return $this->redirectToRoute('profil_liste');
        


      }

}
