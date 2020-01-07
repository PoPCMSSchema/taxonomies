<?php
namespace PoP\Taxonomies\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoP\Taxonomies\Routing\RouteNatures as TaxonomyRouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    public function getModulesVarsPropertiesByNature()
    {
        $ret = array();
        $ret[TaxonomyRouteNatures::TAG][] = [
            'module' => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAG],
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
            POP_TAXONOMIES_ROUTE_TAGS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
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
            POP_POSTS_ROUTE_POSTS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
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
