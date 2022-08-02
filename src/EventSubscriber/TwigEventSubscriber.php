<?php
namespace App\EventSubscriber;

use App\Repository\CategoryRepository;
use Twig\Environment;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigEventSubscriber implements EventSubscriberInterface {

    public function __construct(
        private Environment $twig, 
        private CategoryRepository $repository
        ){}

    public function oncontrollerEvent(ControllerEvent $event)
    {
        // On ajoute une variable comprenant toutes les catégories aux varaibles globales de twig
        $this->twig->addGlobal('categoriesMenu', $this->repository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent'
        ];
    }
}