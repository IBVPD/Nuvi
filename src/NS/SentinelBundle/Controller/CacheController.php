<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends Controller
{
    /**
     * @Route("/admin/clear-cache",name="adminClearTranslationCache")
     * @return Response
     */
    public function clearCacheAction(): Response
    {
        try {
            $cacheDir = dirname($this->getParameter('kernel.cache_dir'));

            foreach (['prod', 'dev', 'live'] as $env) {
                array_map('unlink', glob("$cacheDir/$env/translations/*"));
                array_map('unlink', glob("$cacheDir/$env/app*ProjectContainer.php"));
            }

            $this->get('ns_sentinel.detect_changes')->sendChanges('Nathanael', 'nathanael@noblet.ca');
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response('OK');
    }
}
