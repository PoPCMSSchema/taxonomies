<?php
namespace PoP\Taxonomies\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoP\Taxonomies\Routing\RouteNatures as PostRouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    public function getModulesVarsPropertiesByNature()
    {
        $ret = array();
        $ret[TaxonomyRouteNatures::TAG][] = [
            'module' => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAG_FIELDS],
            'conditions' => [
                'scheme' => POP_SCHEME_API,
            ],
        ];
        return $ret;
    }

    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        $ret = array();
        $routemodules = array(
            POP_TAXONOMIES_ROUTE_TAGS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAGLIST_FIELDS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                ],
            ];
        }
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_TAGPOSTLIST_FIELDS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TaxonomyRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                ],
            ];
        }
        return $ret;
    }
}