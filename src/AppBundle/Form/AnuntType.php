<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnuntType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('createdAt')
            ->add('isPublished')
            //adaugare campuri in formular
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Anunt'
            //data_class specifica entitatea care va fi adaugata in db.Daca e NULL,nu e legata de nicio entitate

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_anunt';//e obligatoriu !
    }
}
