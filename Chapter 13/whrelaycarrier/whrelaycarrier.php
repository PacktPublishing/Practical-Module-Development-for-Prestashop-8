<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use WebHelpers\WHRelayCarrier\Entity\WHRelayCart;

class WHRelayCarrier extends CarrierModule implements WidgetInterface
{
    private $relays = [['id_relay'=>1,'name'=>'Pickup point - Town hall - BUROS'],['id_relay'=>2,'name'=>'Pickup point - Post office - MORLAAS']];

    public function __construct()
    {
        //Required attributes
        $this->name = 'whrelaycarrier';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'Web Helpers';

        //Optional non-localized attributes
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        //Optional localizable attributes
        $this->confirmUninstall = $this->l('Do you still you want to uninstall this module?');
        $this->description = $this->l('This module displays a new carrier');
        $this->displayName = $this->l('Drive relay delivery carrier');
    }

    public function install()
    {
        return parent::install()
            && $this->createTable()
            && $this->installCarrier()
            && $this->registerHook('actionCarrierUpdate')
            && $this->registerHook('displayAfterCarrier')
            && $this->registerHook('displayAdminOrderSideBottom')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function installCarrier()
    {
        $carrier = new Carrier();
        $carrier->name = "Relay Carrier";
        $carrier->url = "https://www.domainname.ext/follow/@";
        $carrier->delay[Configuration::get('PS_LANG_DEFAULT')] = "24 hours";
        $carrier->is_free = false;
        $carrier->active = true;
        $carrier->deleted = false;
        $carrier->shipping_handling = false;
        //Highest defined range if false, true to disable
        $carrier->range_behavior = false;
        $carrier->is_module = true;
        //Computed by the module
        $carrier->shipping_external = true;
        $carrier->external_module_name = $this->name;
        //Is it custom or range based?
        $carrier->need_range = true;
        $carrier->max_width = 0;
        $carrier->max_height = 0;
        $carrier->max_depth = 0;
        $carrier->max_weight = 0;
        //0 for longest, 9 for fastest
        $carrier->grade = 8;
        if ($carrier->add()) {
            $carrier->setGroups(Group::getAllGroupIds());

            $rangeWeight = new RangeWeight();
            $rangeWeight->id_carrier = $carrier->id;
            $rangeWeight->delimiter1 = '0';
            $rangeWeight->delimiter2 = '1000000';
            $rangeWeight->add();

            $zones = Zone::getZones(true);
            foreach ($zones as $z) {
                $carrier->addZone((int) $z['id_zone']);
            }

            copy(dirname(__FILE__) . '/views/img/parcel.jpg', _PS_SHIP_IMG_DIR_ . '/' . (int) $carrier->id . '.jpg'); //assign carrier logo

            Configuration::updateValue('WHRELAYCARRIER_ID', $carrier->id);
            Configuration::updateValue('WHRELAYCARRIER_ID_REFERENCE', $carrier->id);
        }
        return true;
    }

    public function uninstall()
    {
        $id_carrier = Configuration::get('WHRELAYCARRIER_ID');
        $carrier = new Carrier($id_carrier);
        $carrier->delete();

        $this->dropTable();
        return parent::uninstall();
    }

    private function createTable()
    {
        return Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whrelaycarrier_relay_cart` (
            `id_relay_cart` int(6) NOT NULL AUTO_INCREMENT,
            `id_relay` int(6) NOT NULL,
            `id_cart` int(6) NOT NULL,
            PRIMARY KEY(`id_relay_cart`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
    }

    private function dropTable()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whrelaycarrier_relay_cart');
    }

    public function hookActionCarrierUpdate($params)
    {
        if ($params['carrier']->id_reference == Configuration::get('WHRELAYCARRIER_ID_REFERENCE')) {
            Configuration::updateValue('WHRELAYCARRIER_ID', $params['carrier']->id);
        }
    }

    private function findCheckedRelayFromCart()
    {
       $entityManager = $this->get('doctrine.orm.entity_manager');
       $relayCartRepository = $this->get('webhelpers.whrelaycarrier.repository.whrelaycart_repository');
       $id_cart = Context::getContext()->cart->id;

       return $relayCartRepository->findOneBy(['cart'=>$id_cart]);
    }

    public function hookDisplayAfterCarrier($params)
    {
        $this->smarty->assign([
            'relays'=>$this->relays,
        ]);

        $relayCart = $this->findCheckedRelayFromCart();
        if(!is_null($relayCart)){
            $this->smarty->assign([
                'id_relay_checked' => $relayCart->getRelay(),
            ]);
        }

        return $this->fetch('module:whrelaycarrier/views/templates/hook/displayAfterCarrier.tpl');
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if($this->context->controller->page_name=="checkout")
        {
            $this->context->controller->registerStylesheet(
                'whrelaycarrier-style',
                $this->_path.'views/css/checkout.css',
                [
                    'media' => 'all',
                    'priority' => 999,
                ]
            );

            $this->context->controller->registerJavascript(
                'whrelaycarrier-checkout',
                $this->_path.'views/js/checkout.js',
                [
                  'priority' => 999,
                  'attribute' => 'async',
                  'version' => '1.0'
                ]
            );

            $relayCart = $this->findCheckedRelayFromCart();
            $id_relay = 0;
            if(!is_null($relayCart)){
                $id_relay = $relayCart->getRelay();
            }

            Media::addJsDef([
                'ajaxUrl' => Context::getContext()->link->getModuleLink($this->name, 'ajax', ['ajax'=>true]),
                'id_cart' => Context::getContext()->cart->id,
                'id_relay' => $id_relay,
                'id_carrier' => Configuration::get('WHRELAYCARRIER_ID'),
            ]);
        }
    }

    private function findRelayNameById($id_relay)
    {
        foreach($this->relays as $relay)
        {
            if($relay['id_relay']==$id_relay)
            {
                return $relay['name'];
            }
        }
        return false;
    }

    public function hookdisplayAdminOrderSideBottom($params)
    {
        $order = new Order((int)$params['id_order']);
        if(!is_null($order) && !is_null($order->id_cart))
        {
            $entityManager = $this->get('doctrine.orm.entity_manager');
            $relayCartRepository = $this->get('webhelpers.whrelaycarrier.repository.whrelaycart_repository');
            $relayCart = $relayCartRepository->findOneBy(['cart'=>$order->id_cart]);
            if(!is_null($relayCart)){
                $this->smarty->assign([
                    'relayName' => $this->findRelayNameById((int)$relayCart->getRelay())
                ]);
                return $this->fetch('module:whrelaycarrier/views/templates/hook/displayAdminOrderSideBottom.tpl');
            }else{
                return false;
            }
        }
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch('module:whrelaycarrier/views/templates/widget/whrelaycarrier.tpl');
    }

    public function getWidgetVariables($hookName , array $configuration)
    {
        return [
            'numberRelays' => count($this->relays),
        ];
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
      return $shipping_cost+2;
    }

    public function getOrderShippingCostExternal($params)
    {
      return 2;
    }

}
