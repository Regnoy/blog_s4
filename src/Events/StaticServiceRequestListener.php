<?php


namespace App\Events;


use App\Kernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class StaticServiceRequestListener
{
	
	private $container;
	
	public function __construct( ContainerInterface $container )
	{
		$this->container = $container;
	}
	
	public function onKernelRequest(GetResponseEvent $event){
		if( !Kernel::$staticServices){
			Kernel::$staticServices = $this->container;
		}
	}
}