<?php

namespace Blast\BaseEntitiesBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\DataTransformer\ModelToIdPropertyTransformer;

class SearchIndexAutocompleteType extends ModelAutocompleteType
{
//    /**
//     * {@inheritdoc}
//     */
//    public function getParent()
//    {
//        return 'entity';
//    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->addViewTransformer(new ModelToIdPropertyTransformer($options['model_manager'], $options['class'], $options['property'], $options['multiple'], $options['to_string_callback']), true);

       // $builder->setAttribute('property', $options['property']);
        $builder->setAttribute('callback', $options['callback']);
        $builder->setAttribute('minimum_input_length', $options['minimum_input_length']);
        $builder->setAttribute('items_per_page', $options['items_per_page']);
        $builder->setAttribute('req_param_name_page_number', $options['req_param_name_page_number']);
        $builder->setAttribute(
            'disabled',
            $options['disabled']
            // NEXT_MAJOR: Remove this when bumping Symfony constraint to 2.8+
            || (array_key_exists('read_only', $options) && $options['read_only'])
        );
        $builder->setAttribute('to_string_callback', $options['to_string_callback']);

        if ($options['multiple']) {
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
            'admin_code' => 'librinfo_email.admin.email',
            'callback' => $callback,
            'multiple' => false,
            'width' => '',
            'context' => '',
            'property' => '',
            'placeholder' => '',
            'minimum_input_length' => 3, //minimum 3 chars should be typed to load ajax data
            'items_per_page' => 10, //number of items per page
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

        $resolver->setRequired(array('property'));
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
