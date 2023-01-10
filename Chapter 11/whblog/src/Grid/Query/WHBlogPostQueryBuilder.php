<?php

namespace WebHelpers\WHBlog\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\DoctrineFilterApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class WHBlogPostQueryBuilder extends AbstractDoctrineQueryBuilder
{

    private $searchCriteriaApplicator;
    private $filterApplicator;
    private $contextIdLang;


    public function __construct(Connection $connection, $dbPrefix, DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator, DoctrineFilterApplicatorInterface $filterApplicator, int $contextIdLang)
    {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->filterApplicator = $filterApplicator;
        $this->contextIdLang = $contextIdLang;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());

        $this->searchCriteriaApplicator
            ->applyPagination($searchCriteria, $qb)
            ->applySorting($searchCriteria, $qb)
        ;

        return $qb;
    }

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
            ->select('whp.id_post, whpl.title')
            ->from($this->dbPrefix.'whblog_post', 'whp')
            ->leftJoin('whp', $this->dbPrefix . 'whblog_post_lang', 'whpl', 'whp.`id_post` = whpl.`id_post` AND whpl.`id_lang` = :contextIdLang')
            ->setParameter('contextIdLang', $this->contextIdLang);

        foreach ($filterValues as $filterName => $filter) {
            if($filterName == 'id_category'){
                $query->innerJoin('whp', $this->dbPrefix . 'whblog_category_post', 'whcp', 'whp.`id_post` = whcp.`id_post` AND whcp.`id_category` = :idCategory');
                $query->setParameter('idCategory', $filter);
            }
        }

        return $query;
    }
}
