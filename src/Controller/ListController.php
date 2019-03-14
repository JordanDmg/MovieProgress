<?php

namespace App\Controller;

use App\Form\ListType;
use App\Entity\Listing;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
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
    public function modifyList($id)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Listing::class)->findOneBy(
            array('id' => $id));


        return $this->render('list/modifyList.html.twig', [
            'list'    => $list
        ]);
    }
    /**
     * @Route("profil/addMovie/{id}", name="addMovie")
     */
    public function addMovie($id) {
        return $this->render('list/test.html.twig', [
            'id'    => $id
        ]);
    }
}
