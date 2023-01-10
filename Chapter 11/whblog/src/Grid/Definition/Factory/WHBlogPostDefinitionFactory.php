<?php

namespace WebHelpers\WHBlog\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\ColorColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;

final class WHBlogPostDefinitionFactory extends AbstractGridDefinitionFactory
{

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return "whblogpost";
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Blog posts', [], 'Modules.WHBlog.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id_post'))
                ->setName($this->trans('ID', [], 'Modules.WHBlog.Admin'))
                ->setOptions([
                    'field' => 'id_post',
                ])
            )
            ->add(
                (new DataColumn('title'))
                ->setName($this->trans('Post title', [], 'Modules.WHBlog.Admin'))
                ->setOptions([
                    'field' => 'title',
                ])
            )
            ->add(
                (new ActionColumn('actions'))
                ->setName('Actions')
                ->setOptions([
                    'actions' => (new RowActionCollection())
                    ->add(
                        (new LinkRowAction('edit'))
                        ->setIcon('edit')
                        ->setOptions([
                            'route' => 'admin_whblog_post_edit',
                            'route_param_name' => 'idPost',
                            'route_param_field' => 'id_post'
                        ])
                    )
                    ->add(
                        (new LinkRowAction('delete'))
                        ->setIcon('delete')
                        ->setOptions([
                            'route' => 'admin_whblog_post_delete',
                            'route_param_name' => 'idPost',
                            'route_param_field' => 'id_post'
                        ])
                    ),
                ])
            );
    }
}
