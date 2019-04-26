<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Entity\Map;
use NS\ImportBundle\Repository\ImportRepository;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Description of MapAdminController
 *
 * @author gnat
 */
class MapAdminController extends CRUDController
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cloneAction(Request $request)
    {
        $id = $request->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $clonedObject = clone $object;  // Careful, you may need to overload the __clone method of your object to set its id to null
        $clonedObject->setName($object->getName()." (Clone)");

        $this->admin->create($clonedObject);

        $this->addFlash('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    /**
     * @param Request $request
     * @param Map     $object
     *
     * @return RedirectResponse|null
     */
    protected function preDelete(Request $request, $object): ?RedirectResponse
    {
        /** @var ImportRepository $repo */
        $repo = $this->admin->getModelManager()->getEntityManager(Import::class)->getRepository(Import::class);

        if($repo->getMapResultCount($object) > 0) {
            $object->setActive(false);
            $this->admin->getModelManager()->update($object);

            $this->addFlash(
                'sonata_flash_info',
                $this->trans('Map has import results so cannot be deleted. It has been marked inactive instead.')
            );

            return $this->redirectTo($object);
        }

        return null;
    }
}
