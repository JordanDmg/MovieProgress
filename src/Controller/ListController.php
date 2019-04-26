<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\ListType;
use App\Entity\Listing;
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
            $manager->persist($listing);
            $manager->flush();

            return $this->redirectToRoute('modifyList', [
                'id' => $listing->getId()
                ]);
            
        }

        return $this->render('list/newList.html.twig', [
                'formListing' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/modificationList/{id}", name="modifyList") 
     */
    public function modifyList(Listing $list)
    {
        
        return $this->render('list/modifyList.html.twig', [
            'list'    => $list,
            
        ]);
    }


    /**
     * @Route("profil/addMovie/{idList}/{id}/{title}/{posterPath}", name="addMovie")
     */
    public function addMovie($idList, $id, $title, $posterPath ) {
        
        $title = \urldecode($title);

        $em = $this->getDoctrine()->getManager();

        // Recherche dans la bdd l'id TMDB reçu, et l'ajoute s'il est completement 
        // inconnu de la base de données 
        
        $movie = $em->getRepository(Movie::class)->findOneBy(
            array('idTMDB' => $id)
        );
        if($movie === null){
            $movie = new Movie();
            $movie->setIdTMDB($id);
            $movie->setName($title);
            $movie->setPosterPath($posterPath);
            $em->persist($movie);
            $em->flush();
        }
        
        $list = $em->getRepository(Listing::class)->findOneBy(
            array('id'  => $idList)
        );
        $list->addMovie($movie);
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
}
