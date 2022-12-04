<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class WHReinsurance extends Module
{
    public function __construct()
    {
        //Required attributes
        $this->name = 'whreinsurance';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Web Helpers';

        //Optional non-localized attributes
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = false;

        parent::__construct();

        //Optional localizable attributes
        $this->bootstrap = true;
        $this->confirmUninstall = $this->l('Do you still you want to uninstall this module?');
        $this->description = $this->l('This is a reinsurance module for your front office.');
        $this->displayName = $this->l('Reinsurance block');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayFooterBefore') && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->unregisterHook('displayFooterBefore') && $this->unregisterHook('actionFrontControllerSetMedia');
    }

    public function hookDisplayFooterBefore($params)
    {
        $this->context->smarty->assign([
            'module_img_dir' => $this->_path . 'views/img/',
        ]);
        return $this->display(__FILE__, 'whreinsurance.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'whreinsurance-style',
            $this->_path.'views/css/whreinsurance.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );
    }
}
