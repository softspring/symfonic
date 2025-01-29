<?php

namespace Softspring\Symfonic\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

class AddTwigNamespacesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.native_filesystem');

        // register project namespaces before collections to allow overriding
        foreach ((new Finder())->in(__DIR__.'/../../../templates/bundles')->depth(0)->directories() as $directory) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$directory->getRealPath(), str_replace('Bundle', '', $directory->getBasename())]);
        }
    }
}
