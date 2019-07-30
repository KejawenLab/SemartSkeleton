<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UniqueEntity extends Constraint
{
    private $fields;

    private $repositoryClass;

    public function __construct(array $options = [])
    {
        if (isset($options['value'])) {
            $this->fields = $options['value'];
        }

        if (isset($options['fields'])) {
            $this->fields = $options['fields'];
        }

        if (!is_array($this->fields)) {
            $this->fields = [$this->fields];
        }

        if (!isset($options['repositoryClass'])) {
            throw new \InvalidArgumentException();
        }

        $this->repositoryClass = (string) $options['repositoryClass'];
    }

    public function getRepositoryClass(): string
    {
        return $this->repositoryClass;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function validatedBy(): string
    {
        return UniqueEntityValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
