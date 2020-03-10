<?php

namespace MSBios\Doctrine\ORM;

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

    /**
     * @param $params
     * @return array
     */
    public function findByParams($params): array
    {
        $filters = $params->get('filters');
        $orders = $params->get('orders');
        $pagination = $params->get('pagination');

        $alias = $this->getAlias();
        $qb = $this->createQueryBuilder($alias);

        if ($filters) {
            $this->applyFilters($filters, $qb, $alias, $this->getClassName());
        }

        $this->applyOrder($orders ?: ['id' => 'DESC'], $qb, $alias, $this->getClassName());


        return $this->applyPagination($pagination, $qb);
    }

    /**
     * @param array|null $filters
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCount(?array $filters): int
    {
        $alias = $this->getAlias();
        $qb = $this->createQueryBuilder($alias);

        if ($filters) {
            $this->applyFilters($filters, $qb, $alias, $this->getClassName());
        }

        $count = (int)$qb
            ->select($qb->expr()->count($this->getAlias() . '.id'))
            ->getQuery()
            ->getSingleScalarResult();

        return $count;
    }

    /**
     * @param string $column
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRange(string $column): array
    {
        $alias = $this->getAlias();
        $qb = $this->createQueryBuilder($alias);
        $field =  $alias . '.' . $column;

        $result = $qb
            ->select($qb->expr()->min($field) . ' as min')
            ->addSelect($qb->expr()->max($field) . ' as max')
            ->where($qb->expr()->isNotNull("{$alias}.images"))
            ->getQuery()
            ->getSingleResult();

        return $result;
    }

    public function getUniqueValues(string $column): array
    {
        $alias = $this->getAlias();
        $qb = $this->createQueryBuilder($alias);
        $field =  $alias . '.' . $column;

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
     * @param array $order
     * @param QueryBuilder $qb
     * @param string $alias
     * @param string $className
     */
    protected function applyOrder(array $order, QueryBuilder $qb, string $alias, string $className): void
    {
        $metadata = $this->getEntityManager()->getClassMetadata($className);
        $columns = $metadata->getFieldNames();
        $relationColumns = $metadata->getAssociationMappings();
        foreach ($order as $field => $value) {
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
     * @param iterable $filters
     * @param QueryBuilder $qb
     * @param string $alias
     * @param string $className
     */
    protected function applyFilters(iterable $filters, QueryBuilder $qb, string $alias, string $className): void
    {
        $metadata = $this->getEntityManager()->getClassMetadata($className);
        $columns = $metadata->getFieldNames();
        $relationColumns = $metadata->getAssociationMappings();

        foreach ($filters as $field => $value) {

            $column = $alias . '.' . $field;

            if (isset($relationColumns[$field]) && !\in_array($field, $qb->getAllAliases(), true)) {
                $qb->leftJoin($column, $field);
                $this->applyFilters($value, $qb, $field, $relationColumns[$field]['targetEntity']);
                continue;
            }

            if (!\in_array($field, $columns, true)) {
                continue;
            }

            if (\is_array($value) && !empty($value) && \in_array(\key($value), Operator::ALL, true)) {
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

            $operator = $value['operator'] ?? (\is_array($value) ? $operator = Operator::IN : Operator::EQUAL);
            $value = $value['value'] ?? $value;

            switch ($operator) {
                case (Operator::EQUAL):
                    $this->eq($qb, $column, $value);
                    break;
                case (Operator::NOT_EQUAL):
                    $this->neq($qb, $column, $value);
                    break;
                case (Operator::IN):
                    $this->in($qb, $column, $value);
                    break;
                case (Operator::NOT_IN):
                    $this->nin($qb, $column, $value);
                    break;
                case (Operator::LESS_THAN):
                    $this->lt($qb, $column, $value);
                    break;
                case (Operator::LESS_THAN_OR_EQUAL):
                    $this->lte($qb, $column, $value);
                    break;
                case (Operator::GREAT_THAN):
                    $this->gt($qb, $column, $value);
                    break;
                case (Operator::GREAT_THAN_OR_EQUAL):
                    $this->gte($qb, $column, $value);
                    break;
                case (Operator::IS_NULL):
                    $this->isnull($qb, $column, $value);
                    break;
            }
        }
    }

    private function gt(QueryBuilder $qb, string $column, $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' > :' . $placeholder)
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

    private function eq(QueryBuilder $qb, string $column, $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' = :' . $placeholder)
            ->setParameter($placeholder, $value);

        return $this;
    }

    private function neq(QueryBuilder $qb, string $column, $value): self
    {
        $placeholder = $this->createPlaceholder($column);
        $qb->andWhere($column . ' <> :' . $placeholder)
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
    private function isnull(QueryBuilder $qb, string $column, $value): self
    {
        $operator = $value ? 'IS NULL' : 'IS NOT NULL';
        $qb->andWhere($column . ' ' . $operator);

        return $this;
    }

    private function createPlaceholder(string $name): string
    {
        return (str_replace('.', '_', $name) . ++$this->placeholderCounter);
    }
}