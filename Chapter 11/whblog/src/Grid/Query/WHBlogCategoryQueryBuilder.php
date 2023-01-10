<?php

namespace WebHelpers\WHBlog\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\DoctrineFilterApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class WHBlogCategoryQueryBuilder extends AbstractDoctrineQueryBuilder
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
            ->select('whc.id_category, whcl.title')
            ->from($this->dbPrefix.'whblog_category', 'whc')
            ->leftJoin('whc', $this->dbPrefix . 'whblog_category_lang', 'whcl', 'whc.`id_category` = whcl.`id_category` AND whcl.`id_lang` = :contextIdLang')
            ->setParameter('contextIdLang', $this->contextIdLang);

        return $query;
    }
}
