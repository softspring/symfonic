<?php

namespace Softspring\SfsBuilderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsBuilderBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
