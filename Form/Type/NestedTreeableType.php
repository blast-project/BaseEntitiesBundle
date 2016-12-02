<?php

namespace Blast\BaseEntitiesBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityManager;
use Blast\CoreBundle\Form\AbstractType;

class NestedTreeableType extends AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $object_id = ($view->vars['name'] == 'treeParent') ? $form->getParent()->getData()->getId() : null;

        $choices = [];
        foreach ($view->vars['choices'] as $choice) {
                if ( $object_id && $choice->data->getId() == $object_id )
                    $choice->attr['disabled'] = 'disabled';
            
            $choices[] = $choice;
        }
        $view->vars['choices'] = $choices;
    }

    public static function createChoiceLabel($choice)
    {
        $level = $choice->getTreeLvl();
        return str_repeat('- ', $level) . (string) $choice;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choice_label' => array(NestedTreeableType::class, 'createChoiceLabel')
        ));

        $query_builder = function (Options $options) {
            return $options['em']
                    ->getRepository($options['class'])
                    ->getNodesHierarchyQueryBuilder();
        };
        $resolver->setDefault('query_builder', $query_builder);

        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {
            if (is_callable($queryBuilder)) {
                $queryBuilder = call_user_func($queryBuilder, $options['em']->getRepository($options['class']));

                if (!$queryBuilder instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
                }
            }
            return $queryBuilder;
        };
        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);
    }

    public function getParent()
    {
        return 'entity';
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getBlockPrefix()
    {
        return 'librinfo_nested_treeable';
    }
 }
