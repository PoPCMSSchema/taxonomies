<?php
namespace PoP\Taxonomies\Hooks\RESTFields;

use PoP\Engine\Hooks\AbstractHookSet;

class PostHooks extends AbstractHookSet
{
    const TAG_RESTFIELDS = 'tags.id|name|url';

    protected function init()
    {
        $this->hooksAPI->addFilter(
            'Posts:RESTFields',
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields.','.self::TAG_RESTFIELDS;
    }
}
