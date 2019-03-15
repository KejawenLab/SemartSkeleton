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
 * @UniqueEntity(fields={"name"}, repositoryMethod="findUniqueBy", message="label.crud.non_unique_or_deleted")
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
}

```

- Run `php bin/console doctrine:schema:update --force`

- Run `php bin/console semart:generate "KejawenLab\Semart\Skeleton\Entity\Todo"`

- Create translation `translations/messages.id.yaml`

- Yeeeaaaahhhhhh!!! Your crud is generated

> **Tips:** Use [MakerBundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html) to generate entity and repository

## Change Menu

By default, your menu is already registered in root. To edit the menu, open **Menu** menu and search `Todo` and then click **edit** button.

## Setting Permission

## Customize Template

