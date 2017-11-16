<?php

namespace HackerNewsGTD;

use Carbon\Carbon;

class ItemNode
{

    /**
     * @var null|int
     */
    private $id;
    /**
     * @var string
     */
    private $author;

    /**
     * @var null|string
     */
    private $text;

    /**
     * @var null|string
     */
    private $title;

    /**
     * @var null|int
     */
    private $time;

    /**
     * @var null|int
     */
    private $score;

    /**
     * @var null|string
     */
    private $url;

    /**
     * @var null|string
     */
    private $itemType;

    /**
     * @var ItemNode[]
     */
    private $children = [];


    public function __construct($json)
    {
        $this->id       = (int) $json['id'];
        $this->author   = $json['by'];
        $this->text     = array_key_exists('text', $json) ? $json['text'] : null;
        $this->title    = array_key_exists('title', $json) ? $json['title'] : null;
        $this->score    = array_key_exists('score', $json) ? (int) $json['score'] : null;
        $this->time     = Carbon::createFromTimestamp((int) $json['time']);
        $this->url      = array_key_exists('url', $json) ? $json['url'] : null;
        $this->itemType = $json['type'];
    }

    /**
     * Get the item id
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the item id
     * @param int|string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the author
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the author
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get the text of the item
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text of the item
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get the title of the item
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title of the item
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the time
     * @return Carbon|null
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the time
     * @param int $time
     */
    public function setTime($time)
    {
        $this->time = new Carbon($time);
    }

    /**
     * Get the score
     * @return int|null
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set the score
     * @param int $score
     */
    public function setScore($score)
    {
        $this->score = (int) $score;
    }

    /**
     * Get the itemType
     * @return string|null
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * Set the itemType
     * @param string $itemType
     */
    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    /**
     * Get the url of the entry
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url of the item
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get the time in human difference from now
     * @return Carbon|null
     */
    public function getTimeDiffForHumans()
    {
        return $this->time->diffForHumans();
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(ItemNode $child)
    {
        $this->children[] = $child;
    }

    public function dropChildren(ItemNode $child)
    {
        $this->children = [];
    }

    public function countNodes()
    {
        $count = 1;
        foreach ($this->getChildren() as $child) {
            $count += $child->countNodes();
        }
        return $count;
    }
}
