<?php

use HackerNewsGTD\HackerNewsClient;
use HackerNewsGTD\ItemNode;
use Tree\Node\Node;

class ItemMapper
{
    private $client;

    public function __construct($scheme, $host, $apiPath)
    {
        $this->client = new HackerNewsClient($scheme, $host, $apiPath);
    }

    /**
     * Make a tree based on hacker news json
     * @param $id the id of the item
     *
     * @return Node|null
     */
    public function fetchItemTree($id)
    {
        try {
            $jsonItem = $this->client->requestItem($id);
        } catch (Exception $e) {
            // For any reason the status code wasnt 200
            // Log what happened. And then continue with tree building
            return null;
        }

        // Check the item is not soft deleted in HackerNews
        if (array_key_exists('deleted', $jsonItem) && $jsonItem['deleted']) {
            return null;
        }

        $item = new ItemNode($jsonItem);
        $tree = new Node($item);

        if (array_key_exists('kids', $jsonItem)) {
            foreach ($jsonItem['kids'] as $id) {
                $node = $this->getItemTree($id);
                if ($node) {
                    $tree->addChild($node);
                }
            }
        }

        return $tree;
    }

    /**
     * @return []Node
     */
    public function fetchArrayItemsTreeRecursive($ids)
    {
        $jsonItems = $this->client->requestItemsAsync($ids);

        $nodes = [];
        $ids = [];
        // Check the item is not soft deleted in HackerNews
        foreach ($jsonItems as $jsonItem) {
            if (!array_key_exists('deleted', $jsonItem) || !$jsonItem['deleted']) {
                $node = new Node(new ItemNode($jsonItem));

                if (array_key_exists('kids', $jsonItem)) {
                    $children = $this->fetchArrayItemsTreeRecursive($jsonItem['kids']);
                    if ($children) {
                        $node->setChildren($children);
                    }
                }
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    /**
     * Fetch ItemNodes from HackerNews and compose the tree. I do it in
     * array to be able to make the necesary requests concurrent,
     * speeding up the process
     * @param int[], int
     * @return ItemNode
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
     * Fetch ItemNodes from HackerNews and compose the tree. I do it in
     * array to be able to make the necesary requests concurrent,
     * speeding up the process
     * @param int $numberOfStories the number of stories to fetch
     * @param int $offset the number of stories to offset
     * @return ItemNode
     */
    public function fetchNewest($numberOfStories, $offset = 0)
    {
        $topStories = $this->client->requestTopStories();
        $rootIds = array_slice($topStories, $offset, $numberOfStories);
        $tree = $this->fetchArrayItemsTree($rootIds);
        return $tree;
    }
}
