<?php
namespace PoP\Taxonomies\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\Taxonomies\TypeResolvers\TagTypeResolver;

class PostQueryableFieldResolver extends AbstractQueryableFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(PostTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'tags',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'tags' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'tags' => $translationAPI->__('Tags added to this post', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'tags':
                return $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'tags':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $taxonomyapi = \PoP\Taxonomies\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'tags':
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $taxonomyapi->getPostTags(
                    $typeResolver->getId($post),
                    $options
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'tags':
                return TagTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName, $fieldArgs);
    }
}
