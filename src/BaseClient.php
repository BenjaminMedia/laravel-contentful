<?php

namespace Bonnier\Contentful;

use GuzzleHttp\Client;

abstract class BaseClient extends Client
{
    const API = null;

    public function __construct($spaceId, $apiKey, $options = [])
    {
        parent::__construct(array_merge($options, [
            'base_uri' => "https://".static::API.".contentful.com/spaces/$spaceId/",
            'headers' => [
                'authorization' => 'Bearer ' . $apiKey
            ]
        ]));
    }
}