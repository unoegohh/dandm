<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new \Sonata\MediaBundle\SonataMediaBundle(),
            new \Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            //new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new \Sonata\BlockBundle\SonataBlockBundle(),

            new FOS\UserBundle\FOSUserBundle(),

            new Unoegohh\MarkupBundle\UnoegohhMarkupBundle()
,
            new Unoegohh\UserBundle\UnoegohhUserBundle(),
            new Unoegohh\AdminBundle\UnoegohhAdminBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Unoegohh\StaticPageBundle\UnoegohhStaticPageBundle(),
            new Unoegohh\ShopBundle\UnoegohhShopBundle(),
            new Unoegohh\EbayBundle\UnoegohhEbayBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
