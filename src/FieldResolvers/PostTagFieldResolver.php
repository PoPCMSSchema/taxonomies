<?php
namespace PoP\Taxonomies\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoP\Taxonomies\TypeResolvers\TagTypeResolver;

class PostTagFieldResolver extends AbstractPostFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(TagTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'posts' => $translationAPI->__('IDs of the posts which contain this tag', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array {

        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $tag = $resultItem;
        switch ($fieldName) {
            case 'posts':
                $query['tag-ids'] = [$typeResolver->getId($tag)];
                break;
        }

        return $query;
    }
}
