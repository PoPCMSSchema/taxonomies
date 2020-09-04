<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\Facades;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TaxonomyTypeAPIFacade
{
    public static function getInstance(): TaxonomyTypeAPIInterface
    {
        /**
         * @var TaxonomyTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get('taxonomy_type_api');
        return $service;
    }
}
