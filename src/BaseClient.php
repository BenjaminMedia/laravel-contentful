<?php

namespace Bonnier\Contentful;

use GuzzleHttp\Client;

abstract class BaseClient extends Client
{
    const API = null;

    public function __construct($spaceId, $apiKey)
    {
        parent::__construct([
            'base_uri' => "https://".static::API.".contentful.com/spaces/$spaceId/",
            'headers' => [
                'authorization' => 'Bearer ' . $apiKey
            ]
        ]);
    }
}