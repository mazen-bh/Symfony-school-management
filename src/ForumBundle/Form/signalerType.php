<?php
namespace ForumBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class signalerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reason',ChoiceType::class
            ,array('choices' => [
                'Sale of illegal or regulated goods' => 'Sale of illegal or regulated goods',
                'Violence or dangerous organizations' => 'Violence or dangerous organizations',
                'Hate speech or symbols' => 'Hate speech or symbols',
                'Suicide or self-injury'=>  'Suicide or self-injury'
            ]))
            ->add('Add',SubmitType::class);;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ForumBundle\Entity\signaler'
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'forumbundle_signaler';
    }
}