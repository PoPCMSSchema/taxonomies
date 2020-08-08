<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static $COMPONENT_DIR;
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\CustomPosts\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'pop-schema/migrate-taxonomies',
        ];
    }
}
