<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Request;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use PHLAK\Twine\Str;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestHandler
{
    const REQUEST_TOKEN_NAME = 'APP_CSRF_TOKEN';

    private $propertyAccessor;

    private $validator;

    private $eventDispatcher;

    private $translator;

    /** @var ServiceInterface[] */
    private $services;

    private $errors;

    private $valid = false;

    public function __construct(ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher, TranslatorInterface $translator)
    {
        $this->propertyAccessor = new PropertyAccessor();
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->errors = [];
    }

    public function handle(Request $request, object $object)
    {
        $filterEvent = new RequestEvent($request, $object);
        $this->eventDispatcher->dispatch(Application::REQUEST_EVENT, $filterEvent);

        $reflection = new \ReflectionObject($object);
        if ($parent = $reflection->getParentClass()) {
            $reflection = $parent;
        }

        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE|\ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            $field = $property->getName();
            $value = $request->request->get($field);
            if ('id' !== strtolower($field) && null !== $value && '' !== $value) {
                $this->bindValue($object, $field, $value);
            }
        }

        $this->eventDispatcher->dispatch(Application::PRE_VALIDATION_EVENT, $filterEvent);
        $this->validate($object, $reflection);
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setServices(array $services)
    {
        foreach ($services as $service) {
            $this->addService($service);
        }
    }

    private function addService(ServiceInterface $service)
    {
        $class = explode('\\', get_class($service));
        $key = Str::make(array_pop($class))->lowercase()->replace('service', '')->__toString();
        $this->services[$key] = $service;
    }

    private function getServiceKey(string $field, object $object)
    {
        $key = Str::make($field)->lowercase()->__toString();
        if ('parent' === $key) {
            $class = explode('\\', get_class($object));

            return Str::make(array_pop($class))->lowercase()->__toString();
        }

        return $key;
    }

    private function validate(object $object, \ReflectionClass $reflection): void
    {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $this->errors[] = sprintf('<b><i>%s</i></b>: %s', $this->translator->trans(sprintf('label.%s.%s', strtolower($reflection->getShortName()), strtolower($error->getPropertyPath()))), $this->translator->trans($error->getMessage()));
            }
        } else {
            $this->valid = true;
        }
    }

    private function bindValue(object $object, string $field, $value): void
    {
        try {
            $this->propertyAccessor->setValue($object, $field, $value);
        } catch (\Exception $e) {
            $key = $this->getServiceKey($field, $object);
            if (!array_key_exists($key, $this->services)) {
                throw new \InvalidArgumentException();
            }

            $service = $this->services[$key];
            $this->propertyAccessor->setValue($object, $field, $service->get($value));
        }
    }
}
