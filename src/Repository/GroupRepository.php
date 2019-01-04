<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use KejawenLab\Semart\Skeleton\Entity\Group;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GroupRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
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
