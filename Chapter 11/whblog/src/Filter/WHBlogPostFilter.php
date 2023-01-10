<?php

namespace WebHelpers\WHBlog\Filter;

use PrestaShop\PrestaShop\Core\Search\Filters;

final class WHBlogPostFilter extends Filters
{
    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_post',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}
