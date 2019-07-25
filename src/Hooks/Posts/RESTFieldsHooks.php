<?php
namespace PoP\Taxonomies\Hooks\Posts;
use PoP\Hooks\Facades\HooksAPIFacade;

class RESTFieldsHooks
{
    const TAG_RESTFIELDS = 'tags.id|name|url';

    public function __construct() {
        HooksAPIFacade::getInstance()->addFilter(
            'Posts:RESTFields',
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields)
    {
        return $restFields.','.self::TAG_RESTFIELDS;
    }
}