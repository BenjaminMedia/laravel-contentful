<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class ContentManagementEntriesTestCase extends PHPUnit_Framework_TestCase
{
    public function test_it_gets_entries() {

        $apiKey = 'api-key';
        $spaceId = 'space-id';

        $response = [
            'items' => [
                [
                    'fields' => [
                        'title' => 'test'
                    ]
                ]
            ]
        ];

        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, [], json_encode($response)),
        ]);

        $handler = HandlerStack::create($mock);

        $requestHistory = [];
        $handler->push(Middleware::history($requestHistory));

        $entryService = new \Bonnier\Contentful\ContentManagement\Entries($spaceId, $apiKey, [
            'handler' => $handler
        ]);

        $entries = $entryService->getEntries()->items;

        foreach ($entries as $index => $entry) {

            $expectedEntry = $response['items'][$index];

            foreach ($expectedEntry['fields'] as $fieldName => $value) {
                $this->assertEquals($value, $entry->fields->{$fieldName});
            }
        }

        /* @var GuzzleHttp\Psr7\Request $request */
        $request = $requestHistory[0]['request'];

        // Check that request contained our settings space ID and API key
        $this->assertEquals('Bearer ' . $apiKey,  $request->getHeader('Authorization')[0]);
        $this->assertTrue(str_contains($request->getUri()->getPath(), $spaceId));

    }
}