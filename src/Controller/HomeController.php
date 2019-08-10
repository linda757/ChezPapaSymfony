<?php
// namespace : chemin du Controller
namespace App\Controller; 

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Pour créer une page: 1- une fonction publique (classe), 2-une route (un chemin), 3-une réponse

class HomeController extends Controller{
    /** 
    * Création de la 1ère route
    * @Route("/", name = "homepage")
    *
    */
    
    public function home(){
        $noms = ['Durand'=>'visiteur','François'=>'admin','Dupont'=>'contributeur'];
        return $this ->render('home.html.twig',['titre'=>'Site d\'annonces','acces'=>'visiteur','tableau'=>$noms]);
    }

     /**
      * Montre la page qui salut l'utilisateur 
      * @Route("/hello/{nom}",name = "hello")
      * @Route("/profil",name = "hello-base")
      * @Route("/profil/{nom}/acces/{acces}", name="hello-profil")
      * @return void
      */   

    public function hello($nom = "anonyme",$acces = "visiteur"){
        return $this ->render('hello.html.twig',['title'=>'Page de profil', 'nom'=>$nom, 'acces'=>$acces]); 
    }
}