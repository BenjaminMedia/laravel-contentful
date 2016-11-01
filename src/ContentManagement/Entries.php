<?php

namespace Bonnier\Contentful\ContentManagement;

use Bonnier\Contentful\BaseClient;

class Entries extends BaseClient
{
    const API = 'api';
    const API_RESOURCE = 'locales';

    public function __construct($spaceId, $apiKey)
    {
        parent::__construct($spaceId, $apiKey);
    }

    public function getEntries(Array $params = [], $resolveLinks = false) {

        $entries = json_decode(
            $this->get(static::API_RESOURCE, $params)
                ->getBody()
                ->getContents()
        );

        if($resolveLinks) {
            $entries->items = collect($entries->items)->map(function($entry){
                return $this->resolveLinkedFields($entry);
            });
        }

        return $entries;
    }

    public function getEntry($id, Array $params = []) {

        $entry = json_decode(
            $this->get(static::API_RESOURCE . '/' . $id, $params)
                ->getBody()
                ->getContents()
        );

        return $this->resolveLinkedFields($entry);
    }


    private function resolveLinkedFields($entry) {

        $entry->fields = collect($entry->fields)->map(function($field){
            return collect($field)->map(function($localizedValue){
                if(is_array($localizedValue)) {
                    return collect($localizedValue)->map(function($link){
                        return $this->resolveLink($link);
                    });
                }
                return $this->resolveLink($localizedValue);
            });
        });

        return $entry;
    }

    private function resolveLink($link){
        if(isset($link->sys) && $link->sys->type === 'Link' && $link->sys->linkType === 'Entry') {
            return $this->getEntry($link->sys->id);
        }
        return $link;
    }

    
}