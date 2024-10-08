<?php

namespace Softspring\Symfonic;

use Softspring\Symfonic\DependencyInjection\Compiler\AddTwigNamespacesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonicBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AddTwigNamespacesPass());
    }
}
