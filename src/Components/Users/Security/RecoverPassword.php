<?php


namespace App\Components\Users\Security;



use App\Components\Utils\TokenGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;

class RecoverPassword {

  private $mailer;

  private $twig;

  private $em;

  private $router;

  public function __construct( \Swift_Mailer $mailer, \Twig_Environment $twig, EntityManager $entity_manager, Router $router) {
    $this->mailer = $mailer;
    $this->twig = $twig;
    $this->em = $entity_manager;
    $this->router = $router;
  }
  public function send(User $user){
    $token = TokenGenerator::generateToken();

    $url = $this->router->generate('recover', ['token'=> $token], UrlGeneratorInterface::ABSOLUTE_URL);
    $fullname = $user->getFullname();
    try{
      $template = $this->twig->render('User/MailTemplate/recover.html.twig',[
        'url' => $url,
        'fullname'=> $fullname
      ]);
    } catch (\Exception $e){
      return false;
    }

    $mail = \Swift_Message::newInstance();
    $mail->setFrom('noreplay@userbundle.dev');
    $mail->setTo($user->getEmail());
    $mail->setSubject('Recover password');
    $mail->setBody($template);

    $user->getAccount()->setTokenRecover($token);
    $this->em->persist($user);
    $this->em->flush();
    $status = $this->mailer->send($mail);
    if($status){
      return true;
    } else
      return false;

  }
}