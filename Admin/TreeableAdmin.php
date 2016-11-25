<?php

namespace Librinfo\BaseEntitiesBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;

abstract class TreeableAdmin extends CoreAdmin
{
    /**
     * {@inheritdoc}
     */
    public function create($object)
    {

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $this->prePersist($object);
        foreach ($this->extensions as $extension)
            $extension->prePersist($this, $object);

        $object->setMaterializedPath('');
        $em->persist($object);


        if ($object->getParentNode() !== null)
            $object->setChildNodeOf($object->getParentNode());
        else
            $object->setParentNode(null);

        $this->postPersist($object);
        foreach ($this->extensions as $extension)
            $extension->postPersist($this, $object);

        $em->flush();
        $this->createObjectSecurity($object);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject($id)
    {
        $object = parent::getObject($id);

        $parent_node_id = $object->getParentNodeId();
        $parent_node = $this->getModelManager()->find($this->getClass(), $parent_node_id);
        $object->setParentNode($parent_node);

        return $object;
    }
}

