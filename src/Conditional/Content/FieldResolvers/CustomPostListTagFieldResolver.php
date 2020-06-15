<?php

declare(strict_types=1);

namespace PoP\Taxonomies\Conditional\Content\FieldResolvers;

use PoP\Taxonomies\TypeResolvers\TagTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;

class CustomPostListTagFieldResolver extends AbstractCustomPostListFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(TagTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'customPosts' => $translationAPI->__('Custom posts which contain this tag', 'pop-taxonomies'),
            'customPostCount' => $translationAPI->__('Number of custom posts which contain this tag', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $tag = $resultItem;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
                $query['tag-ids'] = [$typeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}