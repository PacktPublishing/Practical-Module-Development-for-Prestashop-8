<?php

namespace WebHelpers\WHBlog\Form\Type;

use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class WHBlogCategoryType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TranslatableType::class, [
                'type' => TextType::class,
                'label' => $this->trans('Category name', 'Modules.Whblog.Admin'),
                'required' => false,
                'options' => [
                    'constraints' => [
                        new Length([
                            'max' => 255,
                        ]),
                    ],
                ],
            ])
            ->add('save', SubmitType::class);
    }
}
