# Basic Usage

## Generate CRUD Template

- Create new entity, for example `Todo`

```php
<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Contract\Entity\NameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Search\Searchable;
use KejawenLab\Semart\Skeleton\Sort\Sortable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="tbl_todo", indexes={@ORM\Index(name="tbl_todo_search_idx", columns={"nama"})})
 * @ORM\Entity(repositoryClass="KejawenLab\Semart\Skeleton\Repository\TodoRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"name"})
 * @Sortable({"name"})
 *
 * @UniqueEntity(fields={"code"}, repositoryMethod="findUniqueBy", message="label.crud.non_unique_or_deleted")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Todo
{
    use BlameableEntity;
    use NameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;
}

```

- Create repository class `TodoRepository`

```php
<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use KejawenLab\Semart\Skeleton\Entity\Todo;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TodoRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $key = md5(sprintf('%s:%s:%s:%s', __CLASS__, __METHOD__, serialize($criteria), serialize($orderBy)));

        $object = $this->getItem($key);
        if (!$object) {
            $object = parent::findOneBy($criteria, $orderBy);

            $this->cache($key, $object);
        }

        return $object;
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $key = md5(sprintf('%s:%s:%s:%s:%s:%s', __CLASS__, __METHOD__, serialize($criteria), serialize($orderBy), $limit, $offset));

        $objects = $this->getItem($key);
        if (!$objects) {
            $objects = parent::findBy($criteria, $orderBy, $limit, $offset);

            $this->cache($key, $objects);
        }

        return $objects;
    }
}

```

- Run `php bin/console semart:generate "KejawenLab\Semart\Skeleton\Entity\Todo"`

- Yeeeaaaahhhhhh!!! Your crud is generated

> Use [MakerBundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html) to generate entity and repository

## Create Menu

## Setting Permission

## Customize Template

