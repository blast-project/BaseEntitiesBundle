<?php

namespace Blast\BaseEntitiesBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Blast\BaseEntitiesBundle\Form\DataTransformer\ModelToIdTransformer;

/**
 * Form type that uses search indexes for entity search autocompletion
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class SearchIndexAutocompleteType extends ModelAutocompleteType
{   
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {  
        $admin = $options['sonata_field_description']->getAssociationAdmin();
      
        $builder->setAttribute('callback', $options['callback']);
        $builder->setAttribute('minimum_input_length', $options['minimum_input_length']);
        $builder->setAttribute('items_per_page', $options['items_per_page']);
        $builder->setAttribute('req_param_name_page_number', $options['req_param_name_page_number']);
        $builder->setAttribute('disabled', $options['disabled']);
        $builder->setAttribute('to_string_callback', $options['to_string_callback']);

        $builder->addViewTransformer(
            new ModelToIdTransformer(
                $admin->getModelManager(), 
                $admin->getClass(), 
                $options['multiple']
            ), 
            true
        );
        
        if ($options['multiple']) 
        {
            $resizeListener = new ResizeFormListener(
                'hidden', array(), true, true, true
            );
            
            $builder->addEventSubscriber($resizeListener);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $compound = function (Options $options) {
            return $options['multiple'];
        };
        
        $callback = function($admin, $property, $value){
            
            $searchIndex = $admin->getClass() . 'SearchIndex';
            $datagrid = $admin->getDatagrid();
            $queryBuilder = $datagrid->getQuery();
            $alias = $queryBuilder->getRootalias();

            $queryBuilder
                ->leftJoin($searchIndex, 's', 'WITH', $alias . '.id = s.object')
                ->where('s.keyword LIKE :value')
                ->setParameter('value', "%$value%")
            ;
            
           // $datagrid->setValue($property, null, $value);
        };

        $resolver->setDefaults(array(
            'attr' => array(),
            'compound' => $compound,
            'model_manager' => null,
            'class' => null,
            'admin_code' => null,
            'callback' => $callback,
            'multiple' => false,
            'width' => '',
            'context' => '',
            'property' => '',
            'placeholder' => '',
            'minimum_input_length' => 3,
            'items_per_page' => 10,
            'quiet_millis' => 100,
            'cache' => false,

            'to_string_callback' => null,

            // ajax parameters
            'url' => '',
            'route' => array('name' => 'sonata_admin_retrieve_autocomplete_items', 'parameters' => array()),
            'req_params' => array(),
            'req_param_name_search' => 'q',
            'req_param_name_page_number' => '_page',
            'req_param_name_items_per_page' => '_per_page',

            // CSS classes
            'container_css_class' => '',
            'dropdown_css_class' => '',
            'dropdown_item_css_class' => '',

            'dropdown_auto_width' => false,

            'template' => 'SonataAdminBundle:Form/Type:sonata_type_model_autocomplete.html.twig',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blast_search_index_autocomplete';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
