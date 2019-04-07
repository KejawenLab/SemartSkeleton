<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SortQuery implements EventSubscriberInterface
{
    private $annotationReader;

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    public function apply(PaginationEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request) {
            return;
        }

        if ('' === $sortField = $request->query->get('s', '')) {
            return;
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $event->getQueryBuilder();
        $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass()));
        foreach ($annotations as $annotation) {
            if (!$annotation instanceof Sortable) {
                continue;
            }

            $sortableFields = $annotation->getFields();
            if (!\in_array($sortField, $sortableFields)) {
                continue;
            }

            $sort = sprintf('%s.%s', $event->getJoinAlias('root'), $sortField);
            if (false !== strpos($sortField, '.')) {
                $fields = explode('.', $sortField);

                $length = \count($fields);
                foreach ($fields as $key => $field) {
                    if ($key === $length - 1) {
                        continue;
                    }

                    $alias = $event->getJoinAlias($field);
                    if (!$alias) {
                        $random = Application::APP_UNIQUE_NAME;
                        $alias = $random[rand($key, \strlen($random) - 1)];

                        if (0 === $key) {
                            $queryBuilder->leftJoin(sprintf('%s.%s', $event->getJoinAlias('root'), $field), $alias);
                        } else {
                            $queryBuilder->leftJoin(sprintf('%s.%s', $fields[$key - 1], $field), $alias);
                        }
                    }

                    $sort = sprintf('%s.%s', $alias, $fields[$key + 1]);
                }
            }

            $queryBuilder->addOrderBy($sort, 'a' === $request->query->get('d', 'a') ? 'ASC' : 'DESC');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Application::PAGINATION_EVENT => [['apply', -255]],
        ];
    }
}
