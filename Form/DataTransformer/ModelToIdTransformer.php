<?php

/*
 * This file is part of the Libre Informatique Symfony2 projects.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * (c) Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 * (c) Libre Informatique <contact@libre-informatique.Fr>
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blast\BaseEntitiesBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class ModelToIdTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param EntityManager $em
     * @param string        $className
     */
    public function __construct(EntityManager $em, $className)
    {
        $this->entityManager = $em;
        $this->className    = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($newId)
    {
        if (empty($newId) && !in_array($newId, array('0', 0), true)) {
            return;
        }

        return $this->entityManager->find($this->className, $newId);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($entity)
    {
        if (empty($entity)) {
            return;
        }

        if (is_scalar($entity)) {
            throw new \RunTimeException('Invalid argument, object or null required');
        }

        if (!$entity) {
            return;
        }

        if (!$this->entityManager->getUnitOfWork()->isInIdentityMap($entity)) {
            return;
        }

        return implode('~', $this->entityManager->getUnitOfWork()->getEntityIdentifier($entity));
    }
}
