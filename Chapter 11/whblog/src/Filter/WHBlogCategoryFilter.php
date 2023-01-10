<?php

namespace WebHelpers\WHBlog\Filter;

use PrestaShop\PrestaShop\Core\Search\Filters;

final class WHBlogCategoryFilter extends Filters
{
    /**
     * {@inheritdoc}
     */
    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_category',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}
