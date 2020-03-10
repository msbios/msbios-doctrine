<?php

namespace MSBios\Doctrine\ORM;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * Class EntityRepository
 * @package MSBios\Doctrine\ORM
 */
class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    const PAGE = 1;
    const LIMIT = 10;

    /** @var int */
    private $placeholderCounter = 0;
    /** @const EQUAL  */
    const EQUAL = 'EQUAL';
    /** @const NOT_EQUAL */
    const NOT_EQUAL = 'NOT_EQUAL';
    /** @const IN */
    const IN = 'IN';
    /** @const NOT_IN */
    const NOT_IN = 'NOT_IN';
    /** @const LESS_THAN */
    const LESS_THAN = 'LESS_THAN';
    /** @const LESS_THAN_OR_EQUAL */
    const LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL';
    /** @const GREAT_THAN */
    const GREAT_THAN = 'GREAT_THAN';
    /** @const GREAT_THAN_OR_EQUAL */
    const GREAT_THAN_OR_EQUAL = 'GREAT_THAN_OR_EQUAL';
    /** @const IS_NULL */
    const IS_NULL = 'IS_NULL';
    /** @const IS_NOT_NULL */
    const IS_NOT_NULL = 'IS_NOT_NULL';

    /** @var array */
    const ALL = [
        self::EQUAL,
        self::NOT_EQUAL,
        self::IN,
        self::NOT_IN,
        self::LESS_THAN,
        self::LESS_THAN_OR_EQUAL,
        self::GREAT_THAN,
        self::GREAT_THAN_OR_EQUAL,
        self::IS_NULL,
        self::IS_NOT_NULL
    ];

    /**
     * @param iterable $filters
     * @param iterable $order
     * @return array
     */
    public function all(iterable $filters, iterable $order): array
    {
        $alias = $this->getAlias();

        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        if ($filters) {
            $this->applyFilters($filters, $qb, $alias, $this->getClassName());
        }

        $this->applyOrder($order ?: ['id' => 'DESC'], $qb, $alias, $this->getClassName());

        return $qb;

        // return $this->applyPagination($pagination, $qb);
    }

    /**
     * @param array|null $filters
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getCount(?array $filters): int
    {
        /** @var string $alias */
        $alias = $this->getAlias();

        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        if ($filters) {
            $this->applyFilters($filters, $qb, $alias, $this->getClassName());
        }

        return (int)$qb
            ->select($qb->expr()->count($this->getAlias() . '.id'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $column
     * @return array
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getRange(string $column): array
    {
        /** @var string $alias */
        $alias = $this->getAlias();

        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        /** @var string $field */
        $field = $alias . '.' . $column;

        return $qb
            ->select($qb->expr()->min($field) . ' as min')
            ->addSelect($qb->expr()->max($field) . ' as max')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param string $column
     * @return array
     */
    public function getUniqueValues(string $column): array
    {
        /** @var string $alias */
        $alias = $this->getAlias();

        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($alias);

        /** @var string $field */
        $field = $alias . '.' . $column;

        $result = $qb
            ->select($field)
            ->groupBy($field)
            ->orderBy($field)
            ->getQuery()
            ->getScalarResult();

        return \array_column($result, $column);
    }

    /**
     * @return string
     */
    protected function getAlias(): string
    {
        return mb_strtolower(
            \end(\explode('\\', $this->getEntityName()))
        );
    }

    /**
     * @param iterable $filters
     * @param QueryBuilder $qb
     * @param string $alias
     * @param string $className
     */
    protected function applyFilters(
        iterable $filters,
        QueryBuilder $qb,
        string $alias,
        string $className
    ): void
    {
        /** @var ClassMetadata $metadata */
        $metadata = $this->_em->getClassMetadata($className);

        /** @var array $columns */
        $columns = $metadata->getFieldNames();

        /** @var array $relationColumns */
        $relationColumns = $metadata->getAssociationMappings();

        /**
         * @var string $field
         * @var mixed $value
         */
        foreach ($filters as $field => $value) {

            /** @var string $column */
            $column = $alias . '.' . $field;

            if (isset($relationColumns[$field]) && !\in_array($field, $qb->getAllAliases(), true)) {
                $qb->leftJoin($column, $field);
                $this->applyFilters($value, $qb, $field, $relationColumns[$field]['targetEntity']);
                continue;
            }

            if (!\in_array($field, $columns, true)) {
                continue;
            }

            if (\is_array($value) && !empty($value) && \in_array(\key($value), self::ALL, true)) {
                foreach ($value as $operatorType => $operatorValue) {
                    $this->applyFilters([
                        $field => [
                            'operator' => $operatorType,
                            'value' => $operatorValue
                        ]
                    ], $qb, $alias, $className);
                }
                continue;
            }

            $operator = $value['operator'] ?? (\is_array($value) ? $operator = self::IN : self::EQUAL);
            $value = $value['value'] ?? $value;

            switch ($operator) {
                case (self::EQUAL):
                    $this->eq($qb, $column, $value);
                    break;
                case (self::NOT_EQUAL):
                    $this->neq($qb, $column, $value);
                    break;
                case (self::IN):
                    $this->in($qb, $column, $value);
                    break;
                case (self::NOT_IN):
                    $this->nin($qb, $column, $value);
                    break;
                case (self::LESS_THAN):
                    $this->lt($qb, $column, $value);
                    break;
                case (self::LESS_THAN_OR_EQUAL):
                    $this->lte($qb, $column, $value);
                    break;
                case (self::GREAT_THAN):
                    $this->gt($qb, $column, $value);
                    break;
                case (self::GREAT_THAN_OR_EQUAL):
                    $this->gte($qb, $column, $value);
                    break;
                case (self::IS_NULL):
                    $this->isNull($qb, $column, $value);
                case (self::IS_NULL):
                    $this->isNull($qb, $column, $value);
                    break;
            }
        }
    }

    /**
     * @param iterable $orders
     * @param QueryBuilder $qb
     * @param string $alias
     * @param string $className
     */
    protected function applyOrder(
        iterable $orders,
        QueryBuilder $qb,
        string $alias,
        string $className
    ): void
    {
        /** @var ClassMetadata $metadata */
        $metadata = $this->getEntityManager()->getClassMetadata($className);

        /** @var array $columns */
        $columns = $metadata->getFieldNames();

        /** @var array $relationColumns */
        $relationColumns = $metadata->getAssociationMappings();

        /**
         * @var string $field
         * @var mixed $value
         */
        foreach ($orders as $field => $value) {

            /** @var string $column */
            $column = $alias . '.' . $field;

            if (isset($relationColumns[$field]) && !\in_array($field, $qb->getAllAliases(), true)) {
                $qb->leftJoin($column, $field);
                $this->applyOrder($value, $qb, $field, $relationColumns[$field]['targetEntity']);
                continue;
            }

            if (!\in_array($field, $columns, true)) {
                continue;
            }

            $qb->addOrderBy($column, $value);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param int $page
     * @param int $limit
     * @return array
     */
    protected function applyPagination(QueryBuilder $qb, int $page = self::PAGE, int $limit = self::LIMIT): array
    {

        $paginationView = $this->getPaginator()->paginate($qb, $page, $limit);

        header('Pagination-Count: ' . $paginationView->getTotalItemCount());
        header('Pagination-Limit: ' . $limit);
        header('Pagination-Page: ' . $page);

        return $paginationView->getItems();
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     */
    public function __call($method, $arguments): self
    {
        list($qb, $column, $value) = $arguments;

        /** @var string $placeholder */
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($qb->expr()->$method($column, ':' . $placeholder))
            ->setParameter($placeholder, $value);

        return $this;
    }


    /**
     * @param QueryBuilder $qb
     * @param string $column
     * @param $value
     * @return $this
     */
    private function gt(QueryBuilder $qb, string $column, $value): self
    {
        /** @var string $placeholder */
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($qb->expr()->gt($column, ':' . $placeholder))
            ->setParameter($placeholder, $value);

        return $this;
    }

    private function gte(QueryBuilder $qb, string $column, $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' >= :' . $placeholder)
            ->setParameter($placeholder, $value);

        return $this;
    }

    private function lt(QueryBuilder $qb, string $column, $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' < :' . $placeholder)
            ->setParameter($placeholder, $value);

        return $this;
    }

    private function lte(QueryBuilder $qb, string $column, $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' <= :' . $placeholder)
            ->setParameter($placeholder, $value);

        return $this;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $column
     * @param $value
     * @return $this
     */
    private function eq(QueryBuilder $qb, string $column, $value): self
    {
        /** @var string $placeholder */
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($qb->expr()->eq($column, ':' . $placeholder))
            ->setParameter($placeholder, $value);

        return $this;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $column
     * @param $value
     * @return $this
     */
    private function neq(QueryBuilder $qb, string $column, $value): self
    {
        /** @var string $placeholder */
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($qb->expr()->neq($column, ':' . $placeholder))
            ->setParameter($placeholder, $value);

        return $this;
    }

    private function in(QueryBuilder $qb, string $column, array $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' IN (:' . $placeholder . ')')
            ->setParameter($placeholder, $value);

        return $this;
    }

    private function nin(QueryBuilder $qb, string $column, array $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' NOT IN (:' . $placeholder . ')')
            ->setParameter($placeholder, $value);

        return $this;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $column
     * @param $value
     * @return $this
     */
    private function isNull(QueryBuilder $qb, string $column, $value): self
    {
        $qb->andWhere($qb->expr()->isNull($column));
        return $this;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $column
     * @param $value
     * @return $this
     */
    private function isNotNull(QueryBuilder $qb, string $column, $value): self
    {
        $qb->andWhere($qb->expr()->isNotNull($column));
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    private function createPlaceholder(string $name): string
    {
        return (str_replace('.', '_', $name) . ++$this->placeholderCounter);
    }
}