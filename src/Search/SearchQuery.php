<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Search;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Skeleton\AppEvent;
use KejawenLab\Semart\Skeleton\Pagination\FilterPagination;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SearchQuery implements EventSubscriberInterface
{
    private $annotationReader;

    private $joinFields = [];

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    public function apply(FilterPagination $event): void
    {
        $queryBuilder = $event->getQueryBuilder();
        if ('' !== $queryString = $event->getRequest()->query->get('q', '')) {
            $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass()));
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Searchable) {
                    $searchable = $annotation->getFields();
                    foreach ($searchable as $value) {
                        if (false !== strpos($value, '.')) {
                            $fields = explode('.', $value);

                            $length = count($fields);
                            foreach ($fields as $key => $field) {
                                if ($key === $length - 1 || in_array($field, $this->joinFields)) {
                                    continue;
                                }

                                if (0 === $key) {
                                    $queryBuilder->join(sprintf('o.%s', $field), $field);
                                } else {
                                    $queryBuilder->join(sprintf('%s.%s', $fields[$key - 1], $field), $field);
                                }

                                $this->joinFields[] = $field;
                            }

                            $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('%s.%s', $fields[$length - 2], $fields[$length - 1]), $queryBuilder->expr()->literal(sprintf('%%%s%%', $queryString))));
                        } else {
                            $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('o.%s', $value), $queryBuilder->expr()->literal(sprintf('%%%s%%', $queryString))));
                        }
                    }
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppEvent::PAGINATION_EVENT => [['apply', 255]],
        ];
    }
}
