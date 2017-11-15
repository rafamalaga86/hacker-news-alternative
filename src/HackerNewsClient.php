<?php

namespace HackerNewsGTD;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise;

// This layer is in charge of shaping URLs
// scheme://host/path?query
// Following https://en.wikipedia.org/wiki/URL parts
class HackerNewsClient
{
    private $genericUrl;

    private $httpClient;

    public function __construct($scheme, $host, $apiPath)
    {
        $this->genericUrl = $scheme . '://' . $host . $apiPath;
        $this->httpClient = new HttpClient();
    }

    private function buildUrl($path)
    {
        return $this->genericUrl . '/' . $path;
    }

    public function requestItem($itemId)
    {
        $finalUrl = $this->buildUrl('item/' . $itemId . '.json');
        $response = $this->httpClient->get($finalUrl);

        if ($response->getStatusCode() !== 200) {
            throw Exception(
                'Failing requesting: ' . $finalUrl . 'Status code: ' . $response->getStatusCode()
            );
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param $itemIds int|int[] array of item ids
     * @param $filter200 bool wheather we should filter out only status codes of 200
     *
     */
    public function requestItemsAsync($itemIds, $filter200 = true)
    {
        $maxConcurrentRequests = 150;
        $totalResponses = [];
        $offset = 0;

        $itemIds = is_array($itemIds) ? $itemIds : [$itemIds];

        // Prevents for making more than 200 connections at the same time, and slice them
        while ($offset < count($itemIds)) {
            $slice = array_slice($itemIds, $offset, $maxConcurrentRequests);

            $promises = array_map(function ($itemId) {
                $url = $this->buildUrl('item/' . $itemId . '.json');
                return $this->httpClient->getAsync($url);
            }, $slice);

            // Wait on all of the requests to complete. Throws a ConnectException
            // if any of the requests fail
            $responses = Promise\unwrap($promises);

            // Wait for the requests to complete, even if some of them fail
            $responses = Promise\settle($promises)->wait();

            $totalResponses = array_merge($totalResponses, $responses);
            $offset += $maxConcurrentRequests;
        }

        $results = [];

        array_walk($totalResponses, function ($response) use ($filter200, &$results) {
            if (!$filter200 || $response['value']->getStatusCode() === 200) {
                $results[] = json_decode($response['value']->getBody(), true);
            }
        });

        return $results;
    }

    public function requestMaxItem()
    {
        $finalUrl = $this->buildUrl('maxitem.json');
        $response = $this->httpClient->get($finalUrl);

        if ($response->getStatusCode() !== 200) {
            throw Exception(
                'Failing requesting: ' . $finalUrl . 'Status code: ' . $response->getStatusCode()
            );
        }

        // Cast Stream to String. Then String to Int. The item ID
        return (int) (string) $response->getBody();
    }

    public function requestTopStories()
    {
        $finalUrl = $this->buildUrl('topstories.json');
        $response = $this->httpClient->get($finalUrl);

        if ($response->getStatusCode() !== 200) {
            throw Exception(
                'Failing requesting: ' . $finalUrl . 'Status code: ' . $response->getStatusCode()
            );
        }

        return json_decode($response->getBody(), true);
    }
}
