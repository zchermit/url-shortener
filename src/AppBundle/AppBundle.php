<?php

namespace AppBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function boot()
    {
        if (!Type::hasType('enumStatusType')) {
            Type::addType('enumStatusType', 'AppBundle\Doctrine\DBAL\Types\EnumStatusType');
        }
    }
}
