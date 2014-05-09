<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
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
            new NS\SonataBundle\NSSonataBundle(),
            new NS\FlashBundle\NSFlashBundle(),

            new Sonata\CoreBundle\SonataCoreBundle(),
          //new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            new \PunkAve\FileUploaderBundle\PunkAveFileUploaderBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Lunetics\LocaleBundle\LuneticsLocaleBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
