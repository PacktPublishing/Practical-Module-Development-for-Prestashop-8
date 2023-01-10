<?php

class WHBlogPostModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $categoryPostRepository = $this->get('webhelpers.whblog.repository.whblogcategorypost_repository');
        $relationships = $categoryPostRepository->findBy(['post'=>Tools::getValue('id_post')]);

        $categories = [];
        if(!is_null($relationships) && count($relationships)>0)
        {
            foreach($relationships as $relation)
            {
                $id_category = $relation->getCategory()->getId();
                $categoryRepository = $this->get('webhelpers.whblog.repository.whblogcategory_repository');
                $category = $categoryRepository->findOneById($id_category);
                $category_data = ["title"=>$category->getCategoryLangByLangId($this->context->language->id)->getTitle(),"url"=>Context::getContext()->link->getModuleLink('whblog', 'category', array('id_category' => $id_category))];
                $categories[] = $category_data;
            }
        }

        $postRepository = $this->get('webhelpers.whblog.repository.whblogpost_repository');
        $post = $postRepository->findOneById(Tools::getValue('id_post'));
        $this->context->smarty->assign(
        array(
          'title' => $post->getTitleLangs(),
          'content' => $post->getContentLangs(),
          'filename' => $post->getFilename(),
          'categories' => $categories,
        ));
        $this->setTemplate('module:whblog/views/templates/front/post.tpl');
    }

    protected function getBreadcrumbLinks()
    {
        $postRepository = $this->get('webhelpers.whblog.repository.whblogpost_repository');
        $post = $postRepository->findOneById(Tools::getValue('id_post'));

        $categoryPostRepository = $this->get('webhelpers.whblog.repository.whblogcategorypost_repository');
        $relationships = $categoryPostRepository->findBy(['post'=>Tools::getValue('id_post')]);

        $breadcrumb = parent::getBreadcrumbLinks();

        if(!is_null($relationships) && count($relationships)>0){
            $id_category_parent = $relationships[0]->getCategory()->getId();
            $categoryRepository = $this->get('webhelpers.whblog.repository.whblogcategory_repository');
            $category = $categoryRepository->findOneById($id_category_parent);

            $breadcrumb['links'][] = [
                'title' => $category->getCategoryLangByLangId($this->context->language->id)->getTitle(),
                'url' => Context::getContext()->link->getModuleLink('whblog', 'category', array('id_category' => $id_category_parent))
            ];

            $breadcrumb['links'][] = [
                'title' => $post->getPostLangByLangId($this->context->language->id)->getTitle(),
                'url' => Context::getContext()->link->getModuleLink('whblog', 'post', array('id_post' => Tools::getValue('id_post')))
            ];
        }
        return $breadcrumb;
     }
}

