<?php

namespace WebHelpers\WHCallback\Filter;

use PrestaShop\PrestaShop\Core\Search\Filters;
use WebHelpers\WHCallback\Grid\Definition\Factory\WHCallbackRequestDefinitionFactory;

final class WHCallbackRequestFilter extends Filters
{
    protected $filterId = WHCallbackRequestDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_request',
            'sortOrder' => 'desc',
            'filters' => [],
        ];
    }
}
