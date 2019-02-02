<?php
namespace AppBundle\Doctrine\DBAL\Types;

use AppBundle\Entity\Url;

class EnumStatusType extends EnumType
{
    protected $name = 'enumStatusType';
    protected $values = [
        Url::STATUS_NEW,
        Url::STATUS_DELETED,
    ];

}
