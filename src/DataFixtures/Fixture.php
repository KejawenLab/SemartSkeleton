<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture as Base;
use Doctrine\Persistence\ObjectManager;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Security\Service\PasswordEncoderService;
use PHLAK\Twine\Str;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class Fixture extends Base
{
    protected const REF_KEY = 'ref:';

    private $encoder;

    protected $kernel;

    public function __construct(KernelInterface $kernel, PasswordEncoderService $encoder)
    {
        $this->kernel = $kernel;
        $this->encoder = $encoder;
    }

    abstract protected function createNew();

    abstract protected function getReferenceKey(): string;

    public function load(ObjectManager $manager)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        Collection::collect($this->getData())
            ->each(function ($value) use ($accessor, $manager) {
                $entity = $this->createNew();

                Collection::collect($value)
                    ->each(function ($value, $key) use ($accessor, $entity) {
                        if (self::REF_KEY === sprintf('%s:', $key)) {
                            $this->setReference(Str::make(sprintf('%s#%s', $this->getReferenceKey(), $value))->uppercase()->__toString(), $entity);
                        } else {
                            if (\is_string($value) && false !== strpos($value, self::REF_KEY)) {
                                $value = $this->getReference(Str::make(str_replace('ref:', '', $value))->uppercase()->__toString());
                            }

                            if (\is_string($value) && false !== strpos($value, 'date:')) {
                                $value = \DateTime::createFromFormat('Y-m-d', str_replace('date:', '', $value));
                            }

                            $accessor->setValue($entity, $key, $value);
                        }
                    })
                ;

                if ($entity instanceof User) {
                    $this->encoder->encode($entity);
                }

                $manager->persist($entity);
            })
        ;

        $manager->flush();
    }

    protected function getData(): array
    {
        $path = sprintf('%s/fixtures/%s.yaml', $this->kernel->getProjectDir(), $this->getReferenceKey());

        return Yaml::parse((string) file_get_contents($path));
    }
}
