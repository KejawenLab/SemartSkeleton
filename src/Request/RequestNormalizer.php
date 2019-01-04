<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Request;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestNormalizer implements EventSubscriberInterface
{
    public function normalize(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $requests = $request->request->all();
        foreach ($requests as $key => $value) {
            if ('false' === $value) {
                $request->request->set($key, false);
            }

            if ('true' === $value) {
                $request->request->set($key, true);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['normalize', 255]],
        ];
    }
}
