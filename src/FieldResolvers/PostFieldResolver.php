<?php
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
            'cat-name',
            'cat-slugs',
            'tag-names',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
			'cats' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'cat' => SchemaDefinition::TYPE_ID,
            'cat-name' => SchemaDefinition::TYPE_STRING,
            'cat-slugs' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'tag-names' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'cats' => $translationAPI->__('Categories to which this post was added', 'pop-taxonomies'),
            'cat' => $translationAPI->__('Main category to which this post was added', 'pop-taxonomies'),
            'cat-name' => $translationAPI->__('Name of the main category to which this post was added', 'pop-taxonomies'),
            'cat-slugs' => $translationAPI->__('Slugs of the categories to which this post was added', 'pop-taxonomies'),
            'tag-names' => $translationAPI->__('Names of the tags added to this post', 'pop-taxonomies'),
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

            case 'cat-name':
                if ($cat = $typeResolver->resolveValue($post, 'cat', $variables, $expressions, $options)) {
                    return $taxonomyapi->getCategoryName($cat);
                }
                return null;

            case 'cat-slugs':
                return $taxonomyapi->getPostCategories($typeResolver->getID($post), ['return-type' => POP_RETURNTYPE_SLUGS]);

            case 'tag-names':
                return $taxonomyapi->getPostTags($typeResolver->getID($post), ['return-type' => POP_RETURNTYPE_NAMES]);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
