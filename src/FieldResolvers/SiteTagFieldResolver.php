<?php
namespace PoP\Taxonomies\FieldResolvers;

use PoP\API\TypeResolvers\SiteTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Engine\FieldResolvers\SiteFieldResolverTrait;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Taxonomies\FieldResolvers\AbstractTagFieldResolver;
use PoP\Taxonomies\FieldResolvers\RootTagFieldResolverTrait;

class SiteTagFieldResolver extends AbstractTagFieldResolver
{
    use SiteFieldResolverTrait, RootTagFieldResolverTrait;

    public static function getClassesToAttachTo(): array
    {
        return array(SiteTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'tag' => $translationAPI->__('Tag with a specific ID', 'pop-taxonomies'),
			'tags' => $translationAPI->__('Tags in the site', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
