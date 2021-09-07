<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestDemoSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        // 
        $request = $event->getRequest();
        $server = $request->server;

        // Si l'adresse est dans une blacklist
        // alors on peut afficher un message d'erreur
        // if ($server->get('REMOTE_ADDR') === '127.0.0.1') {
        //     $response = new Response('<h1>Vous ne passerez .. PAAAAS !');
        // }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
