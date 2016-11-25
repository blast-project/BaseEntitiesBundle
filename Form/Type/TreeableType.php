<?php

namespace Librinfo\BaseEntitiesBundle\Form\Type;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityManager;
use Blast\CoreBundle\Form\AbstractType;

class TreeableType extends AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $object_id = ($view->vars['name'] == 'parentNode') ? $form->getParent()->getData()->getId() : null;
        $choices = [];
        foreach ($view->vars['choices'] as $choice) {
            $choice->attr['data-node-level'] = $choice->data->getNodeLevel();
            if ( $object_id && $choice->data->getId() == $object_id )
                $choice->attr['disabled'] = 'disabled';
            if ( $choice->data->isRootNode() ) {
                $admin = $this->getAdmin($options);
                $choice->label = $admin->trans('parent_root_node_label');
            }
            $choices[] = $choice;
        }
        $view->vars['choices'] = $choices;
    }

    public static function createChoiceLabel($choice)
    {
        $level = $choice->getNodeLevel() - 1;
        return str_repeat('- - ', $level) . (string) $choice;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'min_node_level' => 0,
            'max_node_level' => 0,
            'choice_label' => array(TreeableType::class, 'createChoiceLabel')
        ));

        $query_builder = function (Options $options) {
            $min = $options['min_node_level'];
            $max = $options['max_node_level'];
            return $options['em']
                    ->getRepository($options['class'])
                    ->createOrderedQB($min, $max);
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

    /**
     * @param array $options
     *
     * @return FieldDescriptionInterface
     *
     * @throws \RuntimeException
     */
    protected function getFieldDescription(array $options)
    {
        if (!isset($options['sonata_field_description'])) {
            throw new \RuntimeException('Please provide a valid `sonata_field_description` option');
        }

        return $options['sonata_field_description'];
    }

    /**
     * @param array $options
     *
     * @return AdminInterface
     */
    protected function getAdmin(array $options)
    {
        return $this->getFieldDescription($options)->getAdmin();
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
        return 'librinfo_treeable';
    }
 }
