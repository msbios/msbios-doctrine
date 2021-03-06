<?php

namespace MSBios\Doctrine;

use MSBios\Doctrine\ORM\EntityRepository;

/**
 * Class EntityFetcher
 *
 * @package MSBios\Doctrine
 */
class EntityFetcher
{
    /** @var EntityRepository */
    private $repository;

    /**
     * EntityFetcher constructor.
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param iterable $filters
     * @param int $page
     * @param int $limit
     */
    public function all(iterable $filters, int $page, int $limit): void
    {

    }
}