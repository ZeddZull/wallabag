<?php

namespace Wallabag\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    private $themes = array();
    private $languages = array();

    /**
     * @param array $themes    Themes come from the LiipThemeBundle (liip_theme.themes)
     * @param array $languages Languages come from configuration, array just code language as key and label as value
     */
    public function __construct($themes, $languages)
    {
        $this->themes = array_combine(
            $themes,
            array_map(function ($s) { return ucwords(strtolower(str_replace('-', ' ', $s))); }, $themes)
        );

        $this->languages = $languages;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theme', ChoiceType::class, array(
                'choices' => array_flip($this->themes),
                'choices_as_values' => true,
            ))
            ->add('items_per_page',IntegerType::class,array('required'=> true))
            ->add('language', ChoiceType::class, array(
                'choices' => array_flip($this->languages),
                'choices_as_values' => true,
            ))
            ->add('enable_carrot', CheckboxType::class, array('required' => false))
            ->add('enable_diaspora', CheckboxType::class, array('required' => false))
            ->add('diaspora_url', UrlType::class, array('required' => false))
            ->add('enable_shaarli', CheckboxType::class, array('required' => false))
            ->add('shaarli_url', UrlType::class, array('required' => false))
            ->add('enable_mail', CheckboxType::class, array('required' => false))
            ->add('enable_twitter', CheckboxType::class, array('required' => false))
            ->add('show_printlink', CheckboxType::class, array('required' => false))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wallabag\CoreBundle\Entity\Config',
        ));
    }

    public function getBlockPrefix()
    {
        return 'config';
    }
}
