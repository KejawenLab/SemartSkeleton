<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use KejawenLab\Semart\Skeleton\Util\UniqueAlias;
use KejawenLab\Semart\Skeleton\Query\CastToStringQuery;
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
        $request = $event->getRequest();
        if (!$request) {
            return;
        }

        if ('' === $queryString = (string) $request->query->get('q', '')) {
            return;
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $event->getQueryBuilder();
        $expr = $queryBuilder->expr();
        $searchs = Collection::collect($this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass())))
            ->filter(static function ($value) {
                if (!$value instanceof Searchable) {
                    return false;
                }

                return true;
            })
            ->map(static function ($value) {
                /* @var Searchable $value */
                return $value->getFields();
            })
            ->flatten()
            ->map(static function ($value) use ($queryBuilder, $expr, $event, $queryString) {
                if (false !== strpos($value, '.')) {
                    $joins = explode('.', $value);
                    $alias = UniqueAlias::generate($event->getJoinFields());

                    $queryBuilder->leftJoin(sprintf('%s.%s', $event->getJoinAlias('root'), $joins[0]), $alias);
                    $event->addJoinAlias($joins[0], $alias);

                    return $expr->like(sprintf('LOWER(TO_TEXT(%s.%s))', $event->getJoinAlias($joins[0]), $joins[1]), $expr->literal(sprintf('%%%s%%', Str::make($queryString)->lowercase())));
                }

                return $expr->like(sprintf('LOWER(TO_TEXT(%s.%s))', $event->getJoinAlias('root'), $value), $expr->literal(sprintf('%%%s%%', Str::make($queryString)->lowercase())));
            })
            ->toArray()
        ;

        $queryBuilder->andWhere($expr->orX(...$searchs));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaginationEvent::class => [['apply', 255]],
        ];
    }
}
