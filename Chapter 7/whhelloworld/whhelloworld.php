<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class WHHelloWorld extends Module
{
    public function __construct()
    {
        //Required attributes
        $this->name = 'whhelloworld';
        $this->tab = 'front_office_features';
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
        $this->bootstrap = true;
        $this->confirmUninstall = $this->l('Do you still you want to uninstall the Hello World module?');
        $this->description = $this->l('This is a simple hello world module.');
        $this->displayName = $this->l('Hello World');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->unregisterHook('displayHome');
    }

    public function hookDisplayHome($params)
    {
        return $this->l("Hello World! I know now how to code a simple module!");
    }
}
