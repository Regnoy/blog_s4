<?php

namespace App\Controller;

use App\Components\Users\Models\RecoverUserModel;
use App\Components\Users\Models\RegisterUserModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\UserAccount;
use App\Forms\RecoverUserForm;
use App\Forms\RegisterUserForm;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends Controller  {

  public function login(AuthenticationUtils $helper){
    $error = $helper->getLastAuthenticationError();
    $lastUsername = $helper->getLastUsername();
    return $this->render('User/Security/login.html.twig', array(
      'last_username' => $lastUsername,
      'error'         => $error,
    ));
  }
  public function registerAction( Request $request ){
    $registerModel = new RegisterUserModel();
    $registerForm = $this->createForm(RegisterUserForm::class, $registerModel);
    $registerForm->handleRequest($request);
    if($registerForm->isSubmitted()){

      $user = $registerModel->getUserHandler();
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      return $this->redirectToRoute('login');
    }
    return $this->render('User/Security/register.html.twig',[
      'register_form' => $registerForm->createView()
    ]);
  }
  public  function recoverAction($token, Request $request ){
    if($token){
      /** @var UserAccount $userRecover */
      $userAccountRecover = $this->getDoctrine()->getRepository('UserBundle:UserAccount')->findOneByTokenRecover($token);
      if($userAccountRecover){

        $userPasswordToken = new UsernamePasswordToken($userAccountRecover->getUser(), null, 'main',$userAccountRecover->getUser()->getRoles() );
        $this->get('security.token_storage')->setToken($userPasswordToken);
        return $this->redirectToRoute('user_password_recover');
      }

    }
    $recoverModel = new RecoverUserModel();
    $recoverForm = $this->createForm(RecoverUserForm::class, $recoverModel);
    $recoverForm->handleRequest($request);
    if($recoverForm->isSubmitted()){
      $email = $recoverModel->getEmail();
      $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByEmail($email);
      if($user){
        $this->get('user.security.recover')->send($user);
      }
      return $this->redirectToRoute('recover');
    }
    return $this->render('User/Security/recover.html.twig',[
      'recover_form' => $recoverForm->createView()
    ]);
  }
}