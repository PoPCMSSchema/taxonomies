<?php
namespace PoP\Taxonomies\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Taxonomies\TypeDataResolvers\TagTypeDataResolver;
use PoP\Taxonomies\TypeResolvers\TagTypeResolver;
use PoP\Taxonomies\Misc\TagHelpers;

class TagFieldResolver extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(TagTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'symbol',
            'symbolnamedescription',
            'namedescription',
            'url',
            'endpoint',
            'symbolname',
            'name',
            'slug',
            'term_group',
            'term_taxonomy_id',
            'taxonomy',
            'description',
            'parent',
            'count',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
			'symbol' => SchemaDefinition::TYPE_STRING,
            'symbolnamedescription' => SchemaDefinition::TYPE_STRING,
            'namedescription' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'endpoint' => SchemaDefinition::TYPE_URL,
            'symbolname' => SchemaDefinition::TYPE_STRING,
            'name' => SchemaDefinition::TYPE_STRING,
            'slug' => SchemaDefinition::TYPE_STRING,
            'term_group' => SchemaDefinition::TYPE_ID,
            'term_taxonomy_id' => SchemaDefinition::TYPE_ID,
            'taxonomy' => SchemaDefinition::TYPE_ID,
            'description' => SchemaDefinition::TYPE_STRING,
            'parent' => SchemaDefinition::TYPE_ID,
            'count' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'symbol' => $translationAPI->__('Tag symbol', 'pop-taxonomies'),
            'symbolnamedescription' => $translationAPI->__('Tag symbol and description', 'pop-taxonomies'),
            'namedescription' => $translationAPI->__('Tag and description', 'pop-taxonomies'),
            'url' => $translationAPI->__('Tag URL', 'pop-taxonomies'),
            'endpoint' => $translationAPI->__('Tag endpoint', 'pop-taxonomies'),
            'symbolname' => $translationAPI->__('Symbol and tag', 'pop-taxonomies'),
            'name' => $translationAPI->__('Tag', 'pop-taxonomies'),
            'slug' => $translationAPI->__('Tag slug', 'pop-taxonomies'),
            'term_group' => $translationAPI->__('TBD', 'pop-taxonomies'),
            'term_taxonomy_id' => $translationAPI->__('TBD', 'pop-taxonomies'),
            'taxonomy' => $translationAPI->__('TBD', 'pop-taxonomies'),
            'description' => $translationAPI->__('Tag description', 'pop-taxonomies'),
            'parent' => $translationAPI->__('ID of the parent category (if this category is a child of another one)', 'pop-taxonomies'),
            'count' => $translationAPI->__('Number of posts containing this tag', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $cmstaxonomiesresolver = \PoP\Taxonomies\ObjectPropertyResolverFactory::getInstance();
        $taxonomyapi = \PoP\Taxonomies\FunctionAPIFactory::getInstance();
        $tag = $resultItem;
        switch ($fieldName) {
            case 'symbol':
                return TagHelpers::getTagSymbol();

            case 'symbolnamedescription':
                return TagHelpers::getTagSymbolNameDescription($tag);

            case 'namedescription':
                return TagHelpers::getTagNameDescription($tag);

            case 'url':
                return $taxonomyapi->getTagLink($typeResolver->getId($tag));

            case 'endpoint':
                return \PoP\API\APIUtils::getEndpoint($typeResolver->resolveValue($resultItem, 'url', $variables, $expressions, $options));

            case 'symbolname':
                return $cmstaxonomiesresolver->getTagSymbolName($tag);

            case 'name':
                return $cmstaxonomiesresolver->getTagName($tag);

            case 'slug':
                return $cmstaxonomiesresolver->getTagSlug($tag);

            case 'term_group':
                return $cmstaxonomiesresolver->getTagTermGroup($tag);

            case 'term_taxonomy_id':
                return $cmstaxonomiesresolver->getTagTermTaxonomyId($tag);

            case 'taxonomy':
                return $cmstaxonomiesresolver->getTagTaxonomy($tag);

            case 'description':
                return $cmstaxonomiesresolver->getTagDescription($tag);

            case 'parent':
                return $cmstaxonomiesresolver->getTagParent($tag);

            case 'count':
                return $cmstaxonomiesresolver->getTagCount($tag);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldDefaultTypeDataResolverClass(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'parent':
                return TagTypeDataResolver::class;
        }

        return parent::resolveFieldDefaultTypeDataResolverClass($typeResolver, $fieldName, $fieldArgs);
    }
}
