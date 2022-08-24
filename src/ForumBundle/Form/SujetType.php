<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SujetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('descriptionF', 'FOS\CKEditorBundle\Form\Type\CKEditorType', array(
            'config' => array('toolbar' => 'full'),
            'input_sync' => true
        ))->add('Captcha', 'Gregwar\CaptchaBundle\Type\CaptchaType',array(
            'width' => 200,
            'height' => 50,
            'length' => 6,
            'quality' => 90,
            'distortion' => true,
            'background_color' => [115, 194, 251],
            'max_front_lines' => 0,
            'max_behind_lines' => 0,
            'attr' => array('class' => 'form-control', 'rows'=> "6")))->add('Valider',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ForumBundle\Entity\Sujet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'forumbundle_sujet';
    }


}
