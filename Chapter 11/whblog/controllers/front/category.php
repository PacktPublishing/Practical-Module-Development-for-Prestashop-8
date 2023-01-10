<?php

class WHBlogCategoryModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $categoryRepository = $this->get('webhelpers.whblog.repository.whblogcategory_repository');
        $postRepository = $this->get('webhelpers.whblog.repository.whblogpost_repository');
        $categoryPostRepository = $this->get('webhelpers.whblog.repository.whblogcategorypost_repository');

        $category = $categoryRepository->findOneById(Tools::getValue('id_category'));
        $relationships = $categoryPostRepository->findBy(['category'=>Tools::getValue('id_category')]);
        $posts = [];
        foreach($relationships as $relation)
        {
            $posts[] = $relation->getPost();
        }

        $this->context->smarty->assign(
        array(
          'title' => $category->getCategoryLangByLangId($this->context->language->id)->getTitle(),
          'posts' => $posts,
          'module_dir' => _PS_MODULE_DIR_ . $this->module->name
        ));
        $this->setTemplate('module:whblog/views/templates/front/category.tpl');
    }
}
