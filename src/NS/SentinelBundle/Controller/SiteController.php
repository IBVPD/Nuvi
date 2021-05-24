<?php declare(strict_types=1);


namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/sites")
 */
class SiteController extends Controller
{
    /**
     * @Route("/search", name="siteSearch")
     */
    public function searchAction(): Response
    {
        return $this->get('ns.ajax_autocompleter')->getAutocomplete(Site::class, ['name', 'code']);
    }
}
