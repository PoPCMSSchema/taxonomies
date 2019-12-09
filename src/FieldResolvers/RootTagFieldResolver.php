<?php
namespace PoP\Taxonomies\FieldResolvers;

use PoP\API\TypeResolvers\RootTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Taxonomies\FieldResolvers\AbstractTagFieldResolver;
use PoP\Taxonomies\FieldResolvers\RootTagFieldResolverTrait;

class RootTagFieldResolver extends AbstractTagFieldResolver
{
    use RootTagFieldResolverTrait;

    public static function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'tag' => $translationAPI->__('ID of the tag', 'pop-taxonomies'),
			'tags' => $translationAPI->__('IDs of the tags in the current site', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
