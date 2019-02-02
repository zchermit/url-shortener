<?php

namespace AppBundle\Doctrine\Filter;

use AppBundle\Entity\Url;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DeletedUrls extends SQLFilter
{
    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->getReflectionClass()->getName() == 'AppBundle\Entity\Url') {
            return sprintf("%s.status != '%s'", $targetTableAlias, Url::STATUS_DELETED);
        }

        return '';
    }
}
