<?php

class WHBlogHomeModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $postRepository = $this->get('webhelpers.whblog.repository.whblogpost_repository');

        $posts = $postRepository->findBy([],['id'=>"DESC"], 3);

        $this->context->smarty->assign(
        array(
          'title' => $this->trans('My shop blog', [], "Modules.Whblog.Shop"),
          'posts' => $posts,
          'module_dir' => _PS_MODULE_DIR_ . $this->module->name
        ));
        $this->setTemplate('module:whblog/views/templates/front/category.tpl');
    }
}
