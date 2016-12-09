<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            new JMS\TranslationBundle\JMSTranslationBundle(),

            new NS\SentinelBundle\NSSentinelBundle(),
            new NS\SecurityBundle\NSSecurityBundle(),
            new NS\UtilBundle\NSUtilBundle(),
            new NS\AceBundle\NSAceBundle(),
            new NS\TranslateBundle\NSTranslateBundle(),
            new NS\AceSonataBundle\NSAceSonataBundle(),
            new NS\AceSonataDoctrineORMAdminBundle\NSSonataDoctrineORMAdminBundle(),
            new NS\ApiDocBundle\NSApiDocBundle(),
            new NS\FlashBundle\NSFlashBundle(),

            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            new PunkAve\FileUploaderBundle\PunkAveFileUploaderBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Lunetics\LocaleBundle\LuneticsLocaleBundle(),

            new NS\ApiBundle\NSApiBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new NS\ImportBundle\NSImportBundle(),

            new Vich\UploaderBundle\VichUploaderBundle(),
            new Leezy\PheanstalkBundle\LeezyPheanstalkBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new \NS\FilteredPaginationBundle\NSFilteredPaginationBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test','live'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
            $bundles[] = new h4cc\AliceFixturesBundle\h4ccAliceFixturesBundle();
        }

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
