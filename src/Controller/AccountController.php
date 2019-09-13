<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher une page de connexion
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $errors = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();


        return $this->render('account/login.html.twig',[
            'hasError'=>$errors!==null,
            'username'=>$username
        ]);
    }

    /**
     * Permet de se déconnecter
     *  @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout(){
        // besoin de rien, tout se passe dans le fichier security.yaml
    }

    /**
     * Permet d'afficher une page d'inscription
     * @Route("/register",name="account_register")
     *
     * @return Response
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder,ObjectManager $manager){
        $user= new User();

        $form=$this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $hash = $encoder->encodePassword($user,$user->getHash());

                    // On modifie le mot de passe avec le setter
                    $user->sethash($hash);

                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash("success","Votre compte a bien été créé");

                    return $this->redirectToRoute("account_login");
            }

        return $this->render("account/register.html.twig",[
            'form'=>$form->createView()
        ]);
    }

    /**
     * Modification du profil utilisateur
     * @Route("/account/profile",name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request,ObjectManager $manager){
        $user=$this->getUser();
        $form=$this->createForm(AccountType::class,$user);
        $form->handlerequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($user);
                $manager->flush();
                $this->addFlash("success","Les informations de votre profil ont bien été modifiées.");    
            }
        return $this->render('account/profile.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * Permet la modification du mot de pass
     * @Route("/account/update-password",name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        $passwordUpdate = new PasswordUpdate;
        $user=$this->getUser();

        $form=$this->createForm(PasswordUpdateType::class,$passwordUpdate);

        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

                // Mdp actuel est le bon
                    if(!password_verify($passwordUpdate->getOldPassword(),$user->getHash())){
                        // message d'erreur
                        //$this->addFlash("warning","Le mot de passe actuel est incorrecte.");
                        $form->get('oldPassword')->addError(new FormError("Le mot de passe saisi n'est pas votre mot de passe actuel."));
                    }else{
                        // On récupère le nouveau mdp
                            $newPassword=$passwordUpdate->getNewPassword();

                        // on crypte le nouveau mdp
                            $hash=$encoder->encodePassword($user,$newPassword);

                        // on modifie le nouveau mdp dans le setter
                            $user->setHash($hash);

                        // on enregistre
                            $manager->persist($user);
                            $manager->flush();

                        // on ajoute un message
                            $this->addFlash("success","Votre nouveau de passe a bien été enregistré.");
                        // on redirige
                            return $this->redirectToRoute('account_profile');
                        }
            }
        
            return $this->render('account/password.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page "Mon compte"
     * @Route("/account",name="account_home")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount(){
        return $this->render("user/index.html.twig",['user'=>$this->getUser()]);
    }

    /**
     * Affichage de la liste des réservation de l'utilisateur
     * @Route("/account/bookings",name="account_bookings")
     *
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }

}
