<?php
namespace PoP\Taxonomies\TypeDataLoaders;

use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataResolver;

class TagTypeDataLoader extends AbstractTypeQueryableDataResolver
{
    public function getDataquery()
    {
        return GD_DATAQUERY_TAG;
    }

    public function getFilterDataloadingModule(): ?array
    {
        return [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAGLIST_FIELDS];
    }

    public function resolveObjectsFromIDs(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        $taxonomyapi = \PoP\Taxonomies\FunctionAPIFactory::getInstance();
        return $taxonomyapi->getTags($query);
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        return $query;
    }

    protected function getOrderbyDefault()
    {
        return NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:tags:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = [])
    {
        $taxonomyapi = \PoP\Taxonomies\FunctionAPIFactory::getInstance();
        return $taxonomyapi->getTags($query, $options);
    }

    public function executeQueryIds($query): array
    {

        // $query['fields'] = 'ids';
        $options = [
            'return-type' => POP_RETURNTYPE_IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
