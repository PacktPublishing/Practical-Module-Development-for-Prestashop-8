<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\CommandBus\TacticianCommandBusAdapter;
use PrestaShopBundle\Form\Admin\Type\CustomContentType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\AddExtensionCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\DeleteExtensionCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\DeleteExtensionFileCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\EditExtensionCommand;

class WHCategoryFields extends Module
{
    private $commandBus;

    public function __construct()
    {
        //Required attributes
        $this->name = 'whcategoryfields';
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
        $this->confirmUninstall = $this->l('Do you still you want to uninstall this module?');
        $this->description = $this->l('This is a module adding fields to the categories.');
        $this->displayName = $this->l('Category fields extension');
    }

    public function install()
    {
        if (parent::install() &&
            $this->createTable() &&
            $this->createLangTable() &&
            $this->registerHook('displayHeaderCategory') &&
            $this->registerHook('actionCategoryFormBuilderModifier') &&
            $this->registerHook('actionAfterCreateCategoryFormHandler') &&
            $this->registerHook('actionAfterUpdateCategoryFormHandler') &&
            $this->registerHook('actionCategoryDelete'))
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

        return parent::uninstall();
    }

    private function createTable()
    {
        return Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whcategoryfields_extension` (
            `id_extension` int(6) NOT NULL AUTO_INCREMENT,
            `id_category` int(6) NOT NULL,
            `filename` varchar(255) NOT NULL,
            `extension_date_add` DATETIME NULL,
            `extension_date_update` DATETIME NULL,
            PRIMARY KEY(`id_extension`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
    }

    private function createLangTable()
    {
        return Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whcategoryfields_extension_lang` (
            `id_extension` int(6) NOT NULL,
            `id_lang` int(6) NOT NULL,
            `short_text` varchar(255) NOT NULL,
            `long_text` text NOT NULL,
            PRIMARY KEY(`id_extension`, `id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
    }

    private function dropTables()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whcategoryfields_extension');
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'whcategoryfields_extension_lang');
    }

    public function hookDisplayHeaderCategory($params)
    {
        $categoryId = Tools::getValue('id_category');
        $extensionRepository = $this->get('webhelpers.whcategoryfields.repository.whcategoryfieldsextension_repository');
        $extension = $extensionRepository->findOneBy(['idCategory'=>(int)$categoryId]);
        $idLang = $this->context->language->getId();
        if(!is_null($extension)){
            $this->context->smarty->assign([
                'short_text' => $extension->getExtensionLangByLangId($idLang)->getShortText(),
                'long_text' => $extension->getExtensionLangByLangId($idLang)->getLongText(),
            ]);
            if($extension->getFilename()!=""){
                $this->context->smarty->assign('filename_path', _MODULE_DIR_."whcategoryfields/views/img/uploads/".$extension->getFilename());
            }
            return $this->display(__FILE__, 'whcategoryfields.tpl');
        }else{
            return "";
        }
    }

    public function hookActionCategoryFormBuilderModifier($params)
    {
        $formBuilder = $params['form_builder'];
        $formBuilder->add('short_text', TranslatableType::class, [
            'type' => TextType::class,
            'label' => $this->trans('Short text field extension', [],'Modules.WHCategoryFields.Admin'),
            'help' => $this->trans('Throws error if length is > 255', [],'Modules.WHCategoryFields.Admin'),
            'required' => false,
            'options' => [
                'constraints' => [
                    new Length([
                        'max' => 255,
                    ]),
                ],
            ],
        ])
        ->add('long_text', TranslatableType::class, [
            'type' => TextareaType::class,
            'label' => $this->trans('Long text field extension', [], 'Modules.WHCategoryFields.Admin'),
            'required' => false,
        ]);

        if(isset($params['id']) && $params['id']>0){
            $extensionRepository = $this->get('webhelpers.whcategoryfields.repository.whcategoryfieldsextension_repository');
            $extension = $extensionRepository->findOneBy(['idCategory'=>$params['id']]);

            if(!is_null($extension)){
                $params['data']['short_text'] = $extension->getShortTextLangs();
                $params['data']['long_text'] = $extension->getLongTextLangs();

                if(isset($extension) && $extension->getFileName()!=""){
                    $link = New Link();
                    $deletionLink = $link->getAdminLink('AdminWHCategoryFields', true, ['route'=>'admin_whcategoryfields_file_delete', 'idCategory'=>$extension->getIdCategory()], []);
                    $formBuilder->add('image_file_icon', CustomContentType::class, [
                        'required' => false,
                        'template' => '@Modules/whcategoryfields/views/templates/admin/upload_image.html.twig',
                        'data' => [
                            'pathDeletion' => $deletionLink,
                            'filenameUrl' => $link->getBaseLink()."/modules/".$this->name."/views/img/uploads/".$extension->getFileName(),
                        ],
                    ]);
                }
            }

        }

        $formBuilder->add('image_file_extension', FileType::class, [
            'label' => 'Image file extension',
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '8024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => $this->trans('Please choose only JPG of PNG files', [], 'Modules.WHCategoryFields.Admin'),
                ])
            ]
        ]);

        $formBuilder->setData($params['data']);
    }

    public function hookActionAfterCreateCategoryFormHandler($params)
    {
        $this->updateWHFieldsExtension($params);
    }

    public function hookActionAfterUpdateCategoryFormHandler($params)
    {
        $this->updateWHFieldsExtension($params);
    }

    private function updateWHFieldsExtension(array $params)
    {
        $this->commandBus = $this->get('prestashop.core.command_bus');
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $extensionRepository = $this->get('webhelpers.whcategoryfields.repository.whcategoryfieldsextension_repository');

        $categoryId = $params['id'];
        $categoryFormData = $params['form_data'];
        $shortText = $categoryFormData['short_text'];
        $longText = $categoryFormData['long_text'];

        $extension = $extensionRepository->findOneBy(['idCategory'=>(int)$categoryId]);

        $fileToUpload = $categoryFormData['image_file_extension'];
        $filename = "";
        if(!is_null($fileToUpload)){
            $originalFilename = pathinfo($fileToUpload->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = uniqid().'.'.$fileToUpload->guessExtension();
            try {
                $fileToUpload->move(
                    _PS_MODULE_DIR_."whcategoryfields/views/img/uploads/",
                    $filename
                );
            } catch (FileException $e) {
            }
            $this->commandBus->handle(new DeleteExtensionFileCommand($categoryId));
        }else{
            $filename=$extension->getFilename();
        }

        if(is_null($extension)){
            $this->commandBus->handle(new AddExtensionCommand(
                $categoryId,
                $shortText,
                $longText,
                $filename
            ));
        }else{

            $this->commandBus->handle(new EditExtensionCommand(
                $extension->getId(),
                $categoryId,
                $shortText,
                $longText,
                $filename
            ));
        }
    }

    public function hookActionCategoryDelete($params)
    {
        $this->commandBus = $this->get('prestashop.core.command_bus');
        $category = $params['category'];
        if(!is_null($category)){
            $this->commandBus->handle(new DeleteExtensionFileCommand($category->id));
            $this->commandBus->handle(new DeleteExtensionCommand($category->id));
        }
    }
}
