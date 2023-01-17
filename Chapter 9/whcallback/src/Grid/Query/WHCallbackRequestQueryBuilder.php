<?php

namespace WebHelpers\WHCallback\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\DoctrineFilterApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;


final class WHCallbackRequestQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var DoctrineSearchCriteriaApplicatorInterface
     */
    private $searchCriteriaApplicator;

    /**
     * @var DoctrineFilterApplicatorInterface
     */
    private $filterApplicator;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     */
    public function __construct(Connection $connection, $dbPrefix, DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator, DoctrineFilterApplicatorInterface $filterApplicator)
    {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->filterApplicator = $filterApplicator;
    }

    // Get Search query builder returns a QueryBuilder that is used to fetch filtered, sorted and paginated data from the database.
    // This query builder is also used to get the SQL query that was executed.
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());

        $this->searchCriteriaApplicator
            ->applyPagination($searchCriteria, $qb)
            ->applySorting($searchCriteria, $qb)
        ;

        return $qb;
    }

    // Get Count query builder that is used to get the total count of all records (products)
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());
        $qb->select('COUNT(*)');
        return $qb;
    }

    // Base query can be used for both Search and Count query builders
    private function getBaseQuery(array $filterValues)
    {
        $query = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from($this->dbPrefix.'whcallback_request', 'whcbr');

        foreach ($filterValues as $filterName => $filter) {
            if($filterName == 'id_request'){
                $query->andWhere('whcbr.`'.$filterName.'` = :'.$filterName);
                $query->setParameter($filterName, $filter);
            }else{
                $query->andWhere('whcbr.`'.$filterName.'` LIKE :'.$filterName);
                $query->setParameter($filterName, "%".$filter."%");
            }

        }

        return $query;
    }
}
