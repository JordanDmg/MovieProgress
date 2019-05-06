<?php

namespace App\Controller;
use App\Form\ListType;
use App\Entity\Listing;

use App\Entity\MovieView;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\ApiManager;



class ProfilController extends AbstractController
{
    /**
     * Fonction d'affichage du menu profil/Films vu 
     * @Route("/profil", name="profil")
     */
    public function profil(ApiManager $api)
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $userMovies = $em->getRepository(MovieView::class)->findMovieByUserId(
            $userId
        );

        return $this->render('profil/profil.html.twig', [
            'controller_name'   => 'Profil',
            'user_movies'        => $userMovies
        ]);
    }

    /**
     * Fonction d'affichage du menu profil/MesListes
     * Emplacement temporaire pour l'instant il est ici parcequ'ils fonctionnent avec l'api et je n'ai pas trouvé de solution
     * @Route("/profil/MesListes", name="profil_liste")
     */
    public function profilListes(Request $request, ObjectManager $manager, UserInterface $user)
    {
        $listing = new Listing();
        $userId = $user->getId();
        //Creation du formulaire pour le boutton créer une Liste
        $form = $this->createForm(ListType::class, $listing);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $lists = $em->getRepository(Listing::class)->findBy(
            array('authorId' => $userId)
        );
        return $this->render('profil/profilListe.html.twig', [
            'formListing' => $form->createView(),
            'user_lists'    => $lists
        ]);
    }
}
