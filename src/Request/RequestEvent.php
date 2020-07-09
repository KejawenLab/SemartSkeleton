<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestEvent extends Event
{
    private $request;

    private $object;

    public function __construct(Request $request, object $object)
    {
        $this->request = $request;
        $this->object = $object;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getObject(): object
    {
        return $this->object;
    }
}
