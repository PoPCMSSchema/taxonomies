<?php
namespace PoP\Taxonomies\Hooks\RESTFields;
use PoP\Hooks\Facades\HooksAPIFacade;

class PostHooks
{
    const TAG_RESTFIELDS = 'tags.id|name|url';

    public function __construct() {
        HooksAPIFacade::getInstance()->addFilter(
            'Posts:RESTFields',
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields.','.self::TAG_RESTFIELDS;
    }
}
