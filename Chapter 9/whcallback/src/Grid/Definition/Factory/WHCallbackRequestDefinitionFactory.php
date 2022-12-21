<?php

namespace WebHelpers\WHCallback\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DateTimeColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;

use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class PromoTypeDefinitionFactory.
 */
final class WHCallbackRequestDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'whcallbackrequest';

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Callback requests', [], 'Modules.WHCallback.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id_request'))
                ->setName($this->trans('ID', [], 'Modules.WHCallback.Admin'))
                ->setOptions([
                    'field' => 'id_request',
                ])
            )
            ->add(
                (new DataColumn('firstname'))
                ->setName($this->trans('First Name', [], 'Modules.WHCallback.Admin'))
                ->setOptions([
                    'field' => 'firstname',
                ])
            )
            ->add(
                (new DataColumn('lastname'))
                ->setName($this->trans('Last Name', [], 'Modules.WHCallback.Admin'))
                ->setOptions([
                    'field' => 'lastname',
                ])
            )
            ->add(
                (new DataColumn('phone'))
                ->setName($this->trans('Phone number', [], 'Modules.WHCallback.Admin'))
                ->setOptions([
                    'field' => 'phone',
                ])
            )
            ->add(
                (new DateTimeColumn('request_date_add'))
                ->setName($this->trans('Requested at', [], 'Modules.WHCallback.Admin'))
                ->setOptions([
                    'field' => 'request_date_add',
                    'format' => 'Y/d/m H:i:s'
                ])
            )
            ->add(
                (new ActionColumn('actions'))
                ->setName('Actions')
                ->setOptions([
                    'actions' => (new RowActionCollection())
                    ->add(
                        (new LinkRowAction('delete'))
                        ->setIcon('delete')
                        ->setOptions([
                            'route' => 'admin_whcallbackrequest_delete',
                            'route_param_name' => 'idRequest',
                            'route_param_field' => 'id_request',
                            'confirm_message' => $this->trans('Delete this request?', [], 'Modules.WHCallback.Admin'),
                        ])
                    ),
                ])
            );
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_request', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search ID', [], 'Modules.WHCallback.Admin'),
                    ],
                ])
                ->setAssociatedColumn('id_request')
            )
            ->add((new Filter('firstname', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search first name', [], 'Modules.WHCallback.Admin'),
                    ],
                ])
                ->setAssociatedColumn('firstname')
            )
            ->add((new Filter('lastname', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search last name', [], 'Modules.WHCallback.Admin'),
                    ],
                ])
                ->setAssociatedColumn('lastname')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setAssociatedColumn('actions')
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search_by_filter_id',
                        'reset_route_params' => [
                            'filterId' => self::GRID_ID,
                        ],
                        'redirect_route' => 'admin_whcallbackrequest_list',
                    ])
            )
        ;
    }
}
