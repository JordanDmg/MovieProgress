<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Movie;
use App\Form\ListType;
use App\Entity\Listing;
use App\Entity\MovieView;
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
     * @Route("/profil/ListePeople/{id}", name="peopleList")
     */
    public function peopleList(Request $request, ObjectManager $manager, UserInterface $user, ApiManager $api, $id)
    {
        $follow = false;
        $apiPeople = $api->getPeopleById($id);
        $people_details = json_decode(($apiPeople["details"]->getBody())->getContents());
        $people_credit = json_decode(($apiPeople["moviecredit"]->getBody())->getContents());

        //Verifi si la personnalité est bien un realisateur, sinon la création de liste est impossible
        if($people_details->known_for_department === "Directing"){

            $em = $this->getDoctrine()->getManager();

            //Verrifie si la liste associer a ce realisateur existe déja dans la base de donnée
            $listFromDatabase = $em->getRepository(Listing::class)->findOneBy(   //Recuparation de l'entité listing s'il existe
                array('peopleId' => $id)
            );
            
            //Si la liste n'existe pas, l'à créer 
            if($listFromDatabase === null){  
                $listFromDatabase = new Listing();
                $listFromDatabase->setName($people_details->name);
                $listFromDatabase->setVisibility(1);
                $listFromDatabase->setType(1);
                $listFromDatabase->setDescription('aucune description');
                $listFromDatabase->setImgPath($people_details->profile_path);
                $listFromDatabase->setAuthorId(9999);
                $listFromDatabase->setPeopleId($id);
                $manager->persist($listFromDatabase);
            
        
                // Pour chaque film sur lequelle la personnalité a travaillé (hors acteur)
                foreach($people_credit->crew as $crew){
                    //Isole les films sur lesquelles il était realisateur
                    if ($crew->job == "Director"){
                        //Verifie si le film existe deja dans la base de données
                        $movie = $em->getRepository(Movie::class)->findOneBy(
                                                array('idTMDB' => $crew->id)
                                            );

                        if($movie === null){
                            $movie = new Movie();
                            $movie->setIdTMDB($crew->id);
                            $movie->setName($crew->title);
                            $movie->setPosterPath($crew->poster_path);
                            if (isset($crew->release_date)&& !empty($crew->release_date)){

                            $movie->setReleaseDate(\DateTime::createFromFormat('Y-m-d',$crew->release_date));

                            }
                            $em->persist($movie);
                            

                        }
                        $listFromDatabase->addMovie($movie);
                        $em->persist($listFromDatabase);
                    }
                }
            }                   // Si la liste existe deja dans la base de données
            else{
                //On compare le nombre de films dans la liste BDD avec le nombre de film presente dans l'API
                $countMovieInList = $listFromDatabase->getMovies()->Count();
                $countMovieRealised = 0;
                dump($countMovieInList);
                foreach($people_credit->crew as $test){
                    if ($test->job == "Director"){    
                        // dump($test);                    
                    $countMovieRealised ++ ;
                    }
                }
                dump($countMovieRealised);
                //S'il y en a plus dans l'API Alors la liste necessite une mise à jour
                if ($countMovieInList < $countMovieRealised) {
                    foreach($people_credit->crew as $crewMovie){

                        if ($crewMovie->job == "Director"){    
                            $tchekMovie = false;
                            foreach ($listFromDatabase->getMovies()->getValues() as $movieInList){
                                    

                                    if ($movieInList->getIdTMDB() == $crewMovie->id){
                                        $tchekMovie = true;
                                        
                                    }

                                    
                            }
                            if(!$tchekMovie){
                                // dump($tchekMovie);
                                $movie = $em->getRepository(Movie::class)->findOneBy(
                                    array('idTMDB' => $crewMovie->id)
                                );

                                if($movie === null){
                                    $movie = new Movie();
                                    $movie->setIdTMDB($crewMovie->id);
                                    $movie->setName($crewMovie->title);
                                    $movie->setPosterPath($crewMovie->poster_path);
                                    if (isset($crewMovie->release_date)&& !empty($crewMovie->release_date)){

                                        $movie->setReleaseDate(\DateTime::createFromFormat('Y-m-d',$crewMovie->release_date));
            
                                        }
                                    $em->persist($movie);
                                    

                                }
                                $listFromDatabase->addMovie($movie);
                                $em->persist($listFromDatabase); 
                            
                            }
                            
                        }
                    }
                }
            }
        }

        $em->flush();

        // Calcul du nombre de film vu dans cette liste en pourcentage : 
        $today = new DateTime();
        $released_movies = array();
        $not_released_movies = array();
        $count_movieView = 0;
        foreach ($listFromDatabase->getMovies() as $movie){

            if (is_null($movie->getReleaseDate())){
                $not_released_movies['9999-99-99'] = $movie;
            }else
            {

                if($movie->getReleaseDate() < $today) {             // si le films est deja sorti
                    $released_movies[$movie->getReleaseDate()->format('Y-m-d')] = $movie;
                    $movieView = $em->getRepository(MovieView::class)->findOneBy(
                        array('movie' => $movie->getID())
                    );
                    if(isset($movieView) && !is_null($movieView)){
                        $count_movieView ++;
                    }
                }else{                                              // si le film n'est pas encore sorti
                    $not_released_movies[$movie->getReleaseDate()->format('Y-m-d')] = $movie;
                }
            }

        }   
        krsort($released_movies);
        ksort($not_released_movies);


        foreach ($listFromDatabase->getUsers() as $user){
            if ($user->getId() == $this->getUser()->getId()) {
                $follow = "oui";
            }
        }

        return $this->render('list/peopleList.html.twig', [
            'list' => $listFromDatabase,
            'authorUsername'        => '$authorUsername',
            'follow'                => $follow,
            'people_details'        => $people_details, 
            'released_movies'       => $released_movies, 
            'not_released_movies'   => $not_released_movies,
            'count_movieView'       => $count_movieView
        ]);
        // return $this->redirectToRoute('readList', [
        //           'id'  =>  $listFromDatabase->getId()
        // ]);
        
       
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
