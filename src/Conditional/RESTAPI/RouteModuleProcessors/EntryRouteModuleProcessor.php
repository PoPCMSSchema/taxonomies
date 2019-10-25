<?php
namespace PoP\Taxonomies\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\Engine_Vars;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\Routing\RouteNatures;
use PoP\Taxonomies\Routing\RouteNatures as TaxonomyRouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    private static $restFieldsQuery;
    private static $restFields;
    public static function getRESTFields(): array
    {
        if (is_null(self::$restFields)) {
            self::$restFields = self::getRESTFieldsQuery();
            if (is_string(self::$restFields)) {
                self::$restFields = FieldQueryConvertorFacade::getInstance()->convertAPIQuery(self::$restFields);
            }
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery(): string
    {
        if (is_null(self::$restFieldsQuery)) {
            $restFieldsQuery = 'id|name|count|url,posts.id|title|date|url';
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                'Tags:RESTFields',
                $restFieldsQuery
            );
        }
        return self::$restFieldsQuery;
    }

    public function getModulesVarsPropertiesByNature()
    {
        $ret = array();
        $vars = Engine_Vars::getVars();
        $ret[TaxonomyRouteNatures::TAG][] = [
            'module' => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAG_FIELDS, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
            'conditions' => [
                'scheme' => POP_SCHEME_API,
                'datastructure' => GD_DATALOAD_DATASTRUCTURE_REST,
            ],
        ];

        return $ret;
    }

    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        $ret = array();
        $vars = Engine_Vars::getVars();
        $routemodules = array(
            POP_TAXONOMIES_ROUTE_TAGS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAGLIST_FIELDS, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                    'datastructure' => GD_DATALOAD_DATASTRUCTURE_REST,
                ],
            ];
        }
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAGPOSTLIST_FIELDS, ['fields' => isset($vars['query']) ? $vars['query'] : \PoP\Posts\RouteModuleProcessors\EntryRouteModuleProcessor::getRESTFields()]],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TaxonomyRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                    'datastructure' => GD_DATALOAD_DATASTRUCTURE_REST,
                ],
            ];
        }
        return $ret;
    }
}
