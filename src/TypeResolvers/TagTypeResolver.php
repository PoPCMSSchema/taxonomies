<?php
namespace PoP\Taxonomies\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Taxonomies\TypeDataResolvers\TagTypeDataResolver;

class TagTypeResolver extends AbstractTypeResolver
{
    public const TYPE_COLLECTION_NAME = 'tags';

    public function getTypeCollectionName(): string
    {
        return self::TYPE_COLLECTION_NAME;
    }

    public function getId($resultItem)
    {
        $cmstaxonomiesresolver = \PoP\Taxonomies\ObjectPropertyResolverFactory::getInstance();
        $tag = $resultItem;
        return $cmstaxonomiesresolver->getTagTermId($tag);
    }

    public function getIdFieldTypeDataResolverClass(): string
    {
        return TagTypeDataResolver::class;
    }
}

