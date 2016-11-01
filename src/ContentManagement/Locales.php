<?php

namespace Bonnier\Contenful\ContentManagement;

use Bonnier\Contentful\BaseClient;

class Locales extends BaseClient
{
    const API = 'api';
    const API_RESOURCE = 'locales';

    public function __construct($spaceId, $apiKey)
    {
        parent::__construct($spaceId, $apiKey);
    }

    public function getLocales() {
        return json_decode(
            $this->get(static::API_RESOURCE)
                ->getBody()
                ->getContents()
        );
    }

    public function getDefaultLocale() {

        $locales = $this->getLocales();

        return collect($locales->items)->first(function($index, $locale){
            return $locale->default;
        });
    }
    
}