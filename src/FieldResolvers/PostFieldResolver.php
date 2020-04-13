<?php

declare(strict_types=1);

namespace PoP\Taxonomies\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\Content\FieldInterfaces\ContentEntityFieldInterfaceResolver;

class PostFieldResolver extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            ContentEntityFieldInterfaceResolver::class,
        ];
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'cats',
            'cat',
            'catName',
            'catSlugs',
            'tagNames',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        // TODO: After implementing the resolver for categories change the type to ID
        $types = [
        'cats' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID/*SchemaDefinition::TYPE_UNRESOLVED_ID*/),
            'cat' => SchemaDefinition::TYPE_ID,//SchemaDefinition::TYPE_UNRESOLVED_ID,
            'catName' => SchemaDefinition::TYPE_STRING,
            'catSlugs' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'tagNames' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'cats' => $translationAPI->__('Categories to which this post was added', 'pop-taxonomies'),
            'cat' => $translationAPI->__('Main category to which this post was added', 'pop-taxonomies'),
            'catName' => $translationAPI->__('Name of the main category to which this post was added', 'pop-taxonomies'),
            'catSlugs' => $translationAPI->__('Slugs of the categories to which this post was added', 'pop-taxonomies'),
            'tagNames' => $translationAPI->__('Names of the tags added to this post', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $taxonomyapi = \PoP\Taxonomies\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'cats':
                return $taxonomyapi->getPostCategories($typeResolver->getID($post), ['return-type' => POP_RETURNTYPE_IDS]);

            case 'cat':
                // Simply return the first category
                if ($cats = $typeResolver->resolveValue($post, 'cats', $variables, $expressions, $options)) {
                    return $cats[0];
                }
                return null;

            case 'catName':
                if ($cat = $typeResolver->resolveValue($post, 'cat', $variables, $expressions, $options)) {
                    return $taxonomyapi->getCategoryName($cat);
                }
                return null;

            case 'catSlugs':
                return $taxonomyapi->getPostCategories($typeResolver->getID($post), ['return-type' => POP_RETURNTYPE_SLUGS]);

            case 'tagNames':
                return $taxonomyapi->getPostTags($typeResolver->getID($post), ['return-type' => POP_RETURNTYPE_NAMES]);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
