<?php
namespace PoP\Taxonomies\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Taxonomies\TypeDataLoaders\TagTypeDataLoader;

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

    public function getTypeDataLoaderClass(): string
    {
        return TagTypeDataLoader::class;
    }
}

