<?php

declare(strict_types=1);

namespace PoP\Taxonomies\Conditional\Content\FieldResolvers;

use PoP\Taxonomies\TypeResolvers\TagTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Content\FieldResolvers\AbstractContentEntityListFieldResolver;

class ContentEntityListTagFieldResolver extends AbstractContentEntityListFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(TagTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'contentEntities' => $translationAPI->__('Entries considered “content” (eg: posts, events) which contain this tag', 'pop-taxonomies'),
            'contentEntityCount' => $translationAPI->__('Number of entries considered “content” (eg: posts, events) which contain this tag', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $tag = $resultItem;
        switch ($fieldName) {
            case 'contentEntities':
            case 'contentEntityCount':
                $query['tag-ids'] = [$typeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
