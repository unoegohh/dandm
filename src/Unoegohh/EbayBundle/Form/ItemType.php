<?php
namespace Unoegohh\EbayBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id','hidden');
        $builder->add('engText', 'textarea', array('required' => false, 'attr' => array('class' => 'span10')));
        $builder->add('ruText', 'textarea', array('required' => false, 'attr' => array('class' => 'span10')));
        $builder->add('category');
        $builder->add('goldType');
    }

    public function getName()
    {
        return 'Item';
    }
}