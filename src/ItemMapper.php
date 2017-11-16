<?php

namespace HackerNewsGTD;

class ItemMapper
{
    /**
     * @var HackerNewsClient
     */
    private $client;


    /**
     * @param HackerNewsClient $client
     */
    public function __construct(HackerNewsClient $client)
    {
        $this->client = $client;
    }


    /**
     * Fetch ItemNodes from HackerNews and compose the tree. I do it in
     * array to be able to make the necessary requests concurrent,
     * speeding up the process
     *
     * @param int[]|int $ids
     * @return ItemNode[]
     */
    public function fetchArrayItemsTree($ids)
    {
        $ids = is_array($ids) ? $ids : [$ids];

        $trees = [];
        $nodeReferences = [];
        while ($ids) {
            $jsonItems = $this->client->requestItemsAsync($ids);
            $ids = [];
            foreach ($jsonItems as $jsonItem) {
                // Check the item is not soft deleted in HackerNews
                if (!array_key_exists('deleted', $jsonItem) || !$jsonItem['deleted']) {
                    $node = new ItemNode($jsonItem);
                    $nodeReferences[$node->getId()] = $node;

                    if (!array_key_exists('parent', $jsonItem)) {
                        $trees[] = $node; // Is a root
                    } else {
                        if (array_key_exists($jsonItem['parent'], $nodeReferences)) {
                            $parentNode = $nodeReferences[$jsonItem['parent']];
                            $parentNode->addChild($node);
                        }
                    }

                    // Aggregates all IDs for all the nodes. Later when we do the
                    // concurrent requests we'll save time
                    if (array_key_exists('kids', $jsonItem)) {
                        $ids = array_merge($ids, $jsonItem['kids']);
                    }
                }
            }
        }

        return $trees;
    }


    /**
     * Slice an array of item ids to be able to request that certain
     * amount of items. This is mainly use to paginate.
     *
     * @param int[] $stories
     * @param int $numberOfItems
     * @param int $offset
     * @return ItemNode[]
     */
    private function sliceAndFetch($stories, $numberOfItems, $offset = 0)
    {
        $rootIds = array_slice($stories, $offset, $numberOfItems);
        $tree = $this->fetchArrayItemsTree($rootIds);
        return $tree;
    }


    /**
     * Fetch top stories
     *
     * @param int $numberOfItems the number of items to fetch
     * @param int $offset the number of items to offset
     * @return ItemNode[]
     */
    public function fetchTopStories($numberOfItems, $offset = 0)
    {
        $stories = $this->client->requestTopStories();
        return $this->sliceAndFetch($stories, $numberOfItems, $offset);
    }


    /**
     * Fetch top stories
     *
     * @param int $numberOfItems the number of items to fetch
     * @param int $offset the number of items to offset
     * @return ItemNode[]
     */
    public function fetchNewestStories($numberOfItems, $offset = 0)
    {
        $stories = $this->client->requestNewestStories();
        return $this->sliceAndFetch($stories, $numberOfItems, $offset);
    }



    /**
     * Fetch top stories
     *
     * @param int $numberOfItems the number of items to fetch
     * @param int $offset the number of items to offset
     * @return ItemNode[]
     */
    public function fetchAskStories($numberOfItems, $offset = 0)
    {
        $stories = $this->client->requestAskStories();
        return $this->sliceAndFetch($stories, $numberOfItems, $offset);
    }



    /**
     * Fetch top stories
     *
     * @param int $numberOfItems the number of items to fetch
     * @param int $offset the number of items to offset
     * @return ItemNode[]
     */
    public function fetchJobs($numberOfItems, $offset = 0)
    {
        $stories = $this->client->requestJobs();
        return $this->sliceAndFetch($stories, $numberOfItems, $offset);
    }



    /**
     * Fetch shows
     *
     * @param int $numberOfItems the number of items to fetch
     * @param int $offset the number of items to offset
     * @return ItemNode[]
     */
    public function fetchShows($numberOfItems, $offset = 0)
    {
        $stories = $this->client->requestShows();
        return $this->sliceAndFetch($stories, $numberOfItems, $offset);
    }
}
