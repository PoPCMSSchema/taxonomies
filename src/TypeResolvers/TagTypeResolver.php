<?php
namespace PoP\Taxonomies\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Taxonomies\TypeDataResolvers\TagTypeDataResolver;

class TagTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'Tag';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getId($resultItem)
    {
        $cmstaxonomiesresolver = \PoP\Taxonomies\ObjectPropertyResolverFactory::getInstance();
        $tag = $resultItem;
        return $cmstaxonomiesresolver->getTagTermId($tag);
    }

    public function getTypeDataResolverClass(): string
    {
        return TagTypeDataResolver::class;
    }
}

