<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTime;

class SecurityController extends AbstractController
{
    /**
     * @Route ("/register", name="security_registration")
     *
     */
    
    public function registration(Request $request, ObjectManager $manager,
        UserPasswordEncoderInterface $encoder) {
        $user = new User() ; 
        $form = $this->createForm(RegistrationType::class, $user );
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user->setSubscribeDate(new \DateTime());
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();



        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     *@Route("/login",name="security_login")
     */
    public function login() {


          return $this->render('security/login.html.twig', [
          ]);

        
    }

    /**
     *@Route("/home",name="home")
     */
    public function home() {


    return $this->render('api/home.html.twig', [
        'controller_name' => 'ApiController',
    ]);

        
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}

}
