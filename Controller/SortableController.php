<?php

namespace Librinfo\BaseEntitiesBundle\Controller;

use Sonata\AdminBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Response;

use Librinfo\BaseEntitiesBundle\Entity\Repository\SearchableRepository;

use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SortableController extends CoreController
{
    /**
     * Move a sortable item
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \RuntimeException
     * @throws AccessDeniedException
     */
    public function moveSortableItemAction(Request $request)
    {
        $admin = $this->container->get('sonata.admin.pool')->getInstance($request->get('admin_code'));
        $admin->setRequest($request);

        return new JsonResponse(array(
            'status' => 'OK',
            'class'   => $admin->getClass(),
        ));
    }

}