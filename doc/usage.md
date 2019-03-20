# Penggunaan Dasar

## Generate CRUD

- Buat entity baru, sebagai contoh `Todo`

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

- Buat repository class-nya, dalam kasus ini class `TodoRepository`

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

- Jalankan perintah `php bin/console doctrine:schema:update --force`

- Jalankan perintah `php bin/console semart:generate "KejawenLab\Semart\Skeleton\Entity\Todo"`

- Ubah translasi pada file `translations/messages.id.yaml` dan tambahkan 

```yaml
label:
    # Hide others translation
    todo:
        name: 'Nama'
```

- Yeeeaaaahhhhhh!!! CRUD Anda sudah jadi!!!

## Ubah Menu

Secara default, CRUD yang dibuat di atas terdaftar pada root menu sebagai berikut:

 ![Todo](doc/imgs/todo.png "Todo")
 
 Untuk mengubahnya, kita masuk ke menu **Menu** lalu klik tombol **Ubah** pada menu `Todo`, dan Anda dapat mengubah menu induk, icon, dan sebagainya seperti pada gambar berikut:
 
 ![Todo Menu](doc/imgs/todo_menu.png "Todo Menu")

## Customize Template

