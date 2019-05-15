<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\MovieView;
use App\Form\EditUserType;
use App\Form\EditPasswordType;
use App\Form\RegistrationType;
use App\Controller\ApiController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route ("/register", name="security_registration")
     *
     */

    public function registration(
        Request $request,
        ObjectManager $manager,
        UserPasswordEncoderInterface $encoder
    ) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSubscribeDate(new \DateTime());
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     *@Route("/login",name="security_login")
     */
    public function login()
    {


        return $this->render('security/login.html.twig', []);
    }

    /**
     *@Route("/home",name="home")
     */
    public function home()
    {


        return $this->render('api/home.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }


    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    { }
    

    /**
     * @Route("/parametre", name="parameter")
     */
    public function parameter (ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder, UserInterface $user) {

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
            

        if ($form->isSubmitted() && $form->isValid()) { 
            dump($form->getData());
            // $user->setSubscribeDate(new \DateTime());
            // $hash = $encoder->encodePassword($user, $user->getPassword());
            // $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('parameter');
        }

        return $this->render('security/parameter.html.twig', [
            'form' => $form->createView()
        ]);
        // dump($user);
        // return $this->render('security/parameter.html.twig');
    }

    /**
     * @Route("/parametre/editPassword", name="editPassword")
     */
    public function ResetPasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
    	$em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
    	$form = $this->createForm(EditPasswordType::class, $user);

    	$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

           
                $newEncodedPassword = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($newEncodedPassword);
            
                $em->persist($user);
                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('parameter');
            
                
             
        }
    	
    	return $this->render('security/resetPassword.html.twig', array(
    		'form' => $form->createView(),
    	));
    }
}
