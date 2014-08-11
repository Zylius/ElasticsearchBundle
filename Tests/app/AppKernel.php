<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new \Fox\DDALBundle\FoxDDALBundle(),
            new Fox\ConnectionsBundle\FoxConnectionsBundle(),
            new \Fox\SeoBundle\FoxSeoBundle(),
            new Fox\ContentBundle\FoxContentBundle(),
            new Fox\UtilsBundle\FoxUtilsBundle(),
            new Crunch\Bundle\SSIBundle\CrunchSSIBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

}
