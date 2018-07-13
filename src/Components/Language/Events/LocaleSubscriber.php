<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 7/13/2018
 * Time: 8:08 AM
 */

namespace App\Components\Language\Events;


use App\Components\Language\CurrentLanguage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class LocaleSubscriber implements EventSubscriberInterface
{

  public function onKernelRequest(GetResponseEvent $event){
    $request = $event->getRequest();
    CurrentLanguage::$language = $request->getLocale();
  }

  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::REQUEST => [ ['onKernelRequest' , 15]]
    ];
  }
}