<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
trait CodeNameableTrait
{
    protected $normalize = true;

    /**
     * @ORM\Column(name="kode", type="string", length=27, unique=true)
     *
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    protected $code;

    /**
     * @ORM\Column(name="nama", type="string", length=77)
     *
     * @Assert\Length(max=77)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    protected $name;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $code = Str::make($code);

        $this->code = $this->normalize ? $code->uppercase()->__toString() : $code->__toString();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $name = Str::make($name);

        $this->name = $this->normalize ? $name->uppercase()->__toString() : $name->__toString();
    }
}
