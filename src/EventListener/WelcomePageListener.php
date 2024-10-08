<?php

namespace Softspring\Symfonic\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class WelcomePageListener implements EventSubscriberInterface
{
    public function __construct(protected bool $debug)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        if ('/' !== $event->getRequest()->getPathInfo()) {
            return;
        }

        if (!$this->debug) {
            return;
        }

        $event->setResponse($this->createWelcomeResponse());
    }

    protected function createWelcomeResponse(): Response
    {
        ob_start();
        include dirname(__DIR__).'/../templates/welcome.html.php';

        return new Response(ob_get_clean(), Response::HTTP_NOT_FOUND);
    }
}
