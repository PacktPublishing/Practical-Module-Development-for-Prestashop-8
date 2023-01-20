<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class WHBlog extends Module
{
    public $tabs = [
        [
            'name' => [
                'en' => 'Blog management', // Fallback value
                'fr' => 'Gestion du Blog'
            ],
            'class_name' => 'AdminWHBlogCategory',
            'route_name' => 'admin_whblog_category_list',
            'parent_class_name' => 'AdminParentThemes',
            'wording' => 'Blog management', // Ignored in PS < 1.7.8
            'wording_domain' => 'Modules.WHBlog.Admin', // Ignored in PS < 1.7.8
        ]
    ];

    public function __construct()
    {
        //Required attributes
        $this->name = 'whblog';
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
        $this->controllers = ['category','home','post'];

        parent::__construct();

        //Optional localizable attributes
        $this->confirmUninstall = $this->l('Do you still you want to uninstall this module?');
        $this->description = $this->l('This is a simple blog module.');
        $this->displayName = $this->l('Blog');
    }

    public function install()
    {
        if (parent::install() &&
            $this->createTables() &&
            $this->createLangTables())
        {
            return true;
        }else{
            $this->_errors[] = $this->l('There was an error during the installation.');
            return false;
        }
    }

    public function uninstall()
    {
        $this->dropTables();
        $this->dropLangTables();
        return parent::uninstall();
    }

    private function createTables()
    {
        Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whblog_post` (
            `id_post` int(6) NOT NULL AUTO_INCREMENT,
            `filename` varchar(255) NOT NULL,
            `post_date_add` DATETIME NULL,
            `post_date_update` DATETIME NULL,
            PRIMARY KEY(`id_post`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');

        Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whblog_category` (
            `id_category` int(6) NOT NULL AUTO_INCREMENT,
            `category_date_add` DATETIME NULL,
            `category_date_update` DATETIME NULL,
            PRIMARY KEY(`id_category`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');

        Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whblog_category_post` (
            `id_category_post` int(6) NOT NULL AUTO_INCREMENT,
            `id_category` int(6) NOT NULL,
            `id_post` int(6) NOT NULL,
            PRIMARY KEY(`id_category_post`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');

        return true;
    }

    private function createLangTables()
    {
        Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whblog_post_lang` (
            `id_post` int(6) NOT NULL,
            `id_lang` int(6) NOT NULL,
            `title` varchar(255) NOT NULL,
            `content` text NOT NULL,
            PRIMARY KEY(`id_post`, `id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');

        Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whblog_category_lang` (
            `id_category` int(6) NOT NULL,
            `id_lang` int(6) NOT NULL,
            `title` varchar(255) NOT NULL,
            PRIMARY KEY(`id_category`, `id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');

        return true;
    }

    private function dropLangTables()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whblog_category_lang');
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whblog_post_lang');
    }

    private function dropTables()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whblog_category');
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whblog_post');
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whblog_category_post');
    }
}
