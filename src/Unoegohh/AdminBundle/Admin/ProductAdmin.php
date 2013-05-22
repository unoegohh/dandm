<?php
// src/Tutorial/BlogBundle/Admin/PostAdmin.php
namespace Unoegohh\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class ProductAdmin extends Admin
{
    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('price')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Главное')
                ->add('name')
                ->add('smallDesc', 'textarea')
                ->add('descr', 'textarea', array('attr' => array('class' => 'tinymce'), 'required' => false))
                ->add('price')
                ->add('discount', null, array('required' => false))
                ->add('category', 'sonata_type_model', array(),
                    array('link_parameters' =>
                        array('context' => 'default',
                            'provider' => 'sonata.media.provider.image'
                            )
                        )
                    )
                ->add('position', null, array('required' => false))
                ->add('photos', 'sonata_type_model',
                    array(
    //                    'by_reference' => false
                        'required' => false,
                    ),
                    array(
    //                    'edit' => 'inline',
    //                    'inline' => 'table',
    //                    'sortable'  => 'position',
                        'link_parameters' => array(
                            'context' => 'default',
                            'provider' => 'sonata.media.provider.gallery'
                        )
                    )
                )
            ->end()
            ->with('Специфичное', array('collapsed' => true))
                ->add('ringSize', null, array('required' => false))
                ->add('goldType', 'sonata_type_model',  array('required' => false)
                )
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('_action', 'actions', array(
            'actions' => array(
                'view' => array(),
                'edit' => array(),
                'delete' => array(),
            )
        ))
        ;
    }
}