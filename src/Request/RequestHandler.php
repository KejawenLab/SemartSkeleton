<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Request;

use KejawenLab\Semart\Skeleton\AppEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $filterEvent = new FilterRequest($request, $object);

        $this->eventDispatcher->dispatch(AppEvent::REQUEST_EVENT, $filterEvent);

        $reflection = new \ReflectionObject($object);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE|\ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $property) {
            $field = $property->getName();
            $value = $request->request->get($field);
            if ('id' !== strtolower($field) && null !== $value && '' !== $value) {
                $this->propertyAccessor->setValue($object, $field, $value);
            }
        }

        $this->eventDispatcher->dispatch(AppEvent::PRE_VALIDATION_EVENT, $filterEvent);

        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $this->errors[] = sprintf('<b><i>%s</i></b>: %s', $this->translator->trans(sprintf('label.%s.%s', strtolower($reflection->getShortName()), strtolower($error->getPropertyPath()))), $error->getMessage());
            }
        } else {
            $this->valid = true;
        }
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
