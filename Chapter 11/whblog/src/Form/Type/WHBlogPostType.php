<?php

namespace WebHelpers\WHBlog\Form\Type;

use PrestaShopBundle\Form\Admin\Type\CategoryChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\CustomContentType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class WHBlogPostType extends TranslatorAwareType
{
    private $categoryRepository;
    private $categoriesChoiceTree;

    public function __construct($translator, $locales, $categoryRepository)
    {
        parent::__construct($translator, $locales);
        $this->categoryRepository = $categoryRepository;
    }

    private function buildChoiceTree($id_lang)
    {
        $this->categoriesChoiceTree = [];
        $categories = $this->categoryRepository->findAll();
        foreach($categories as $category)
        {
            $name = $category->getCategoryLangByLangId($id_lang)->getTitle();
            if($name=="")
            {
                $name = "No title - ID: ".$category->getId();
            }
            $this->categoriesChoiceTree[] = ['id_category'=>(string)$category->getId(), 'name'=>$name];
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildChoiceTree($this->locales[0]["id_lang"]);
        $builder
            ->add('title', TranslatableType::class, [
                'type' => TextType::class,
                'label' => $this->trans('Post title', 'Modules.Whblog.Admin'),
                'required' => false,
                'options' => [
                    'constraints' => [
                        new Length([
                            'max' => 255,
                        ]),
                    ],
                ],
            ])
            ->add('content', TranslatableType::class, [
                'type' => TextareaType::class,
                'label' => $this->trans('Post content', 'Modules.Whblog.Admin'),
                'required' => false
            ]);

        if(isset($options['data']['image_file_preview']['filenameUrl']) && $options['data']['image_file_preview']['filenameUrl']!="")
        {
           $builder->add('image_file_preview', CustomContentType::class, [
               'required' => false,
               'template' => '@Modules/whblog/views/templates/admin/upload_image.html.twig',
               'data' => $options['data']['image_file_preview'],
            ]);
        }


        $builder->add('image_file', FileType::class, [
                'label' => $this->trans('Image file', 'Modules.Whblog.Admin'),
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => $this->trans('Please choose only JPG of PNG files', 'Modules.WHCategoryFields.Admin'),
                    ])
                ]
            ])
            ->add('categories', CategoryChoiceTreeType::class, [
                    'label'=> $this->trans('Categories', 'Modules.Whblog.Admin'),
                    'multiple' => true,
                    'choices_tree' => $this->categoriesChoiceTree,
                ])
            ->add('save', SubmitType::class);
    }
}
