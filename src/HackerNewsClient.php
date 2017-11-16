<?php

namespace HackerNewsGTD;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise;

// This layer is in charge of shaping URLs
class HackerNewsClient
{
    /**
     * @var string
     */
    private $baseUri;


    /**
     * @var GuzzleClient
     */
    private $httpClient;


    /**
     * @param HackerNewsClient $client
     */
    public function __construct(GuzzleClient $httpClient, $baseUri)
    {
        $this->httpClient = $httpClient;
        $this->baseUri = $baseUri;
    }


    /**
     * Builds a string with the final URI to be requested
     *
     * @param  string path on the url
     * @return string
     */
    private function buildUrl($path)
    {
        return $this->baseUri . '/' . $path;
    }


    /**
     * Creates a rejected promise for a reason if the reason is not a promise. If
     * the provided reason is a promise, then it is returned as-is.
     *
     * @param  int[] $itemId
     * @return array json response of the request
     */
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
     * Request json items to the hackernews item endpoint
     *
     * @param  $itemIds int|int[] array of item ids
     * @param  $filter200 bool wheather we should filter out only status codes of 200
     * @return array[] json in shape of assoc arrays of the items requested
     */
    public function requestItemsAsync($itemIds, $filter200 = true, $maxConcurrentRequests = 150)
    {
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


    /**
     * Send a request to maxitem endpoint to get to know newest item
     *
     * @return int The item ID
     */
    public function requestMaxItem()
    {
        $finalUrl = $this->buildUrl('maxitem.json');
        $response = $this->httpClient->get($finalUrl);

        if ($response->getStatusCode() !== 200) {
            throw Exception(
                'Failing requesting: ' . $finalUrl . 'Status code: ' . $response->getStatusCode()
            );
        }

        // Cast Stream to String. Then String to Int.
        return (int) (string) $response->getBody();
    }


    /**
     * Requests IDs of item of type "stories", the ones in the top
     *
     * @return int[]
     */
    public function requestTopStories()
    {
        $finalUrl = $this->buildUrl('topstories.json');
        return $this->requestItemTypeIds($finalUrl);
    }


    /**
     * Requests IDs of item of type "stories", the newest ones
     *
     * @return int[]
     */
    public function requestNewestStories()
    {
        $finalUrl = $this->buildUrl('newstories.json');
        return $this->requestItemTypeIds($finalUrl);
    }


    /**
     * Requests IDs of item of type "ask"
     *
     * @return int[]
     */
    public function requestAskStories()
    {
        $finalUrl = $this->buildUrl('askstories.json');
        return $this->requestItemTypeIds($finalUrl);
    }


    /**
     * Requests IDs of item of type "jobs"
     *
     * @return int[]
     */
    public function requestJobs()
    {
        $finalUrl = $this->buildUrl('jobstories.json');
        return $this->requestItemTypeIds($finalUrl);
    }


    /**
     * Requests IDs of item of type "shows"
     *
     * @return int[]
     */
    public function requestShows()
    {
        $finalUrl = $this->buildUrl('showstories.json');
        return $this->requestItemTypeIds($finalUrl);
    }


    /**
     * Helper method that helps handling the requests of item ids
     *
     * @param  string $finalUrl url to request
     * @return int[] ids of the item
     */
    private function requestItemTypeIds($finalUrl)
    {
        $response = $this->httpClient->get($finalUrl);

        if ($response->getStatusCode() !== 200) {
            throw Exception(
                'Failing requesting: ' . $finalUrl . 'Status code: ' . $response->getStatusCode()
            );
        }

        return json_decode($response->getBody(), true);
    }
}
