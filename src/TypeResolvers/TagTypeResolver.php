<?php
namespace PoP\Taxonomies\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Taxonomies\TypeDataLoaders\TagTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class TagTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'Tag';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a tag, added to a post', 'tags');
    }

    public function getID($resultItem)
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
