<?php

namespace Librinfo\BaseEntitiesBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Librinfo\DoctrineBundle\Entity\Repository\TreeableRepositoryInterface;
use Librinfo\CoreBundle\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Librinfo\BaseEntitiesBundle\Form\DataTransformer\ModelToIdTransformer;

class TreeableType extends AbstractType
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $choices = [];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO :
//        if ($options['multiple']) {
//            $builder->addViewTransformer(new ModelsToArrayTransformer($options['choice_list'], $options['model_manager'], $options['class']), true);
//            $builder->addEventSubscriber(new MergeCollectionListener($options['model_manager']));
//        }
//        else ...

        $builder->resetViewTransformers();
        $builder->addViewTransformer(new ModelToIdTransformer($this->em, $options['class']), true);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event)
        {
            $form = $event->getForm();
            $passed_options = $form->getConfig()->getAttribute('data_collector/passed_options');
            $repo = $this->em->getRepository($passed_options['class']);
            $query = isset($passed_options['tree_query']) ? $passed_options['tree_query'] : 'getRootNodesWithTree';

            if ($repo instanceof TreeableRepositoryInterface)
            {
                $this->choices = $this->buildTreeChoices($repo->$query());

                $form->getConfig()->getType()->getOptionsResolver()->setDefaults([
                    'choices' => $this->choices
                ]);
            }
        });

        parent::buildForm($builder, $options);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['choices'] = [];

        $object_id = ($view->vars['name'] == 'parentNode') ? $form->getParent()->getData()->getId() : null;
        foreach ($this->choices as $choice) {
            if ( $object_id && $choice->data->getId() == $object_id )
                continue;
            if ( $options['min_node_level'] && $choice->data->getNodeLevel() < $options['min_node_level'] )
                continue;
            if ( $options['max_node_level'] && $choice->data->getNodeLevel() > $options['max_node_level'] )
                continue;
            if ( $choice->data->isRootNode() ) {
                $admin = $this->getAdmin($options);
                $choice->label = $admin->trans('parent_root_node_label');
            }

            $view->vars['choices'][] = $choice;
        }
    }

    protected function buildTreeChoices($choices, $level = 0)
    {
        $result = array();

        foreach ($choices as $choice)
        {
            $result[] = new ChoiceView(
                $choice,
                $choice->getId(),
                $this->generateTreeSymbols($choice->getName(), $level),
                []
            );

            if ($choice->getChildNodes()->count() != 0)
                $result = array_merge(
                    $result,
                    $this->buildTreeChoices($choice->getChildNodes(), $level + 1)
                );
        }
        return $result;
    }

    protected function generateTreeSymbols($label, $level)
    {
        return str_repeat('- - ', $level) . $label;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'tree_query' => 'getRootNodesWithTree',
            'min_node_level' => 0,
            'max_node_level' => 0
        ));
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
 }
