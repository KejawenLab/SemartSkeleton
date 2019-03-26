<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use PHLAK\Twine\Str;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SearchQuery implements EventSubscriberInterface
{
    private $annotationReader;

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    public function apply(PaginationEvent $event): void
    {
        if ('' === $queryString = $event->getRequest()->query->get('q', '')) {
            return;
        }

        $queryBuilder = $event->getQueryBuilder();
        $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass()));
        foreach ($annotations as $annotation) {
            if (!$annotation instanceof Searchable) {
                continue;
            }

            $expr = $queryBuilder->expr();
            $filters = [];

            $searchable = $annotation->getFields();
            foreach ($searchable as $value) {
                if (false !== strpos($value, '.')) {
                    $fields = explode('.', $value);

                    $length = count($fields);
                    foreach ($fields as $key => $field) {
                        if ($key === $length - 1 || \in_array($field, $event->getJoinFields())) {
                            continue;
                        }

                        $random = Application::APP_UNIQUE_NAME;
                        $alias  = $random[rand($key, strlen($random)-1)];

                        if (0 === $key) {
                            $queryBuilder->leftJoin(sprintf('%s.%s', $event->getJoinAlias('root'), $field), $alias);
                        } else {
                            $queryBuilder->leftJoin(sprintf('%s.%s', $fields[$key - 1], $field), $alias);
                        }

                        $event->addJoinAlias($field, $alias);
                    }

                    $filters[] = $expr->like(sprintf('LOWER(%s.%s)', $event->getJoinAlias($fields[$length - 2]), $fields[$length - 1]), $expr->literal(sprintf('%%%s%%', Str::make($queryString)->lowercase())));
                } else {
                    $filters[] = $expr->like(sprintf('LOWER(%s.%s)', $event->getJoinAlias('root'), $value), $expr->literal(sprintf('%%%s%%', Str::make($queryString)->lowercase())));
                }
            }

            $queryBuilder->andWhere(call_user_func_array([$expr, 'orX'], $filters));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Application::PAGINATION_EVENT => [['apply', 255]],
        ];
    }
}
