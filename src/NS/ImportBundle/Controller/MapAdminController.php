<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Entity\Map;
use NS\ImportBundle\Repository\ImportRepository;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MapAdminController extends CRUDController
{
    public function cloneAction(Request $request): RedirectResponse
    {
        $id = $request->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $clonedObject = clone $object;  // Careful, you may need to overload the __clone method of your object to set its id to null
        $clonedObject->setName($object->getName(). ' (Clone)');

        $this->admin->create($clonedObject);
        $request->getSession()->getFlashBag()->add('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    protected function preDelete(Request $request, $object): ?RedirectResponse
    {
        /** @var ImportRepository $repo */
        $repo = $this->admin->getModelManager()->getEntityManager(Import::class)->getRepository(Import::class);

        if($repo->getMapResultCount($object) > 0) {
            $object->setActive(false);
            $this->admin->getModelManager()->update($object);

            $request->getSession()->getFlashBag()->add('sonata_flash_info', 'Map has import results so cannot be deleted. It has been marked inactive instead.');

            return $this->redirectTo($object);
        }

        return null;
    }
}
