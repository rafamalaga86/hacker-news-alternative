<?php

require_once __DIR__ . '/../src/ItemNode.php';

use HackerNewsGTD\ItemNode;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class ItemNode
 */
class ItemNodeTest extends TestCase
{
    /**
     * Test getters and setters of text
     *
     * @dataProvider itemJsonProvider
     */
    public function testGetSetText($jsonArray)
    {
        $node = new ItemNode($jsonArray);

        if (array_key_exists('text', $jsonArray)) {
            $this->assertSame($jsonArray['text'], $node->getText());

            $new = $jsonArray['text'] . 'ÑÑ¨´SADFÑ';
            $node->setText($new);
            $this->assertSame($new, $node->getText());
        } else {
            $this->assertNull($node->getText());
        }
    }


    /**
     * Test getters and setters of title
     *
     * @dataProvider itemJsonProvider
     */
    public function testGetSetTitle($jsonArray)
    {
        $node = new ItemNode($jsonArray);

        if (array_key_exists('title', $jsonArray)) {
            $this->assertSame($jsonArray['title'], $node->getTitle());

            $new = $jsonArray['title'] . '_342erw_EF';
            $node->setTitle($new);
            $this->assertSame($new, $node->getTitle());
        } else {
            $this->assertNull($node->getTitle());
        }
    }


    /**
     * Test getters and setters of id
     *
     * @dataProvider itemJsonProvider
     */
    public function testGetSetId($jsonArray)
    {
        $node = new ItemNode($jsonArray);

        if (array_key_exists('id', $jsonArray)) {
            $this->assertSame($jsonArray['id'], $node->getId());

            $new = $jsonArray['id'] . 'rewterwt';
            $node->setId($new);
            $this->assertSame($new, $node->getId());
        } else {
            $this->assertNull($node->getId());
        }
    }


    /**
     * Test getters and setters of author
     *
     * @dataProvider itemJsonProvider
     */
    public function testGetSetAuthor($jsonArray)
    {
        $node = new ItemNode($jsonArray);

        if (array_key_exists('by', $jsonArray)) {
            $this->assertSame($jsonArray['by'], $node->getAuthor());

            $new = $jsonArray['by'] . '_extra';
            $node->setAuthor($new);
            $this->assertSame($new, $node->getAuthor());
        } else {
            $this->assertNull($node->getAuthor());
        }
    }


    /**
     * Test getters and setters of score
     *
     * @dataProvider itemJsonProvider
     */
    public function testGetSetScore($jsonArray)
    {
        $node = new ItemNode($jsonArray);

        if (array_key_exists('score', $jsonArray)) {
            $this->assertSame($jsonArray['score'], $node->getScore());

            $new = $jsonArray['score'] * 345243;
            $node->setScore($new);
            $this->assertSame($new, $node->getScore());
        } else {
            $this->assertNull($node->getScore());
        }
    }


    /**
     * Test getters and setters of Time
     *
     * @dataProvider itemJsonProvider
     */
    public function testGetSetTime($jsonArray)
    {
        $node = new ItemNode($jsonArray);

        if (array_key_exists('time', $jsonArray)) {
            $this->assertSame($jsonArray['time'], $node->getTime()->timestamp);

            $new = $jsonArray['time'] + 5435;
            $node->setTime($new);
            $this->assertSame($new, $node->getTime()->timestamp);
        } else {
            $this->assertNull($node->getTime());
        }
    }


    /**
     * Data provider for tests
     * @return array[]
     */
    public function itemJsonProvider()
    {
        return [
            [
                [
                    "by" => "victorology",
                    "id" => 15712931,
                    "score" => 1,
                    "time" => 1510843384,
                    "title" => "Miso (YC S16) is hiring in our Seoul office",
                    "type" => "job",
                    "url" => "https://about.miso.kr/jobs/"
                ]
            ],
            [
                [
                    "by" => "jeffrese",
                    "descendants" => 8,
                    "id" => 15704122,
                    "kids" => [ 15705456, 15709319, 15704313 ],
                    "score" => 6,
                    "text" => "I started a company this year with the mission...",
                    "time" => 1510757555,
                    "title" => "Ask HN: Equity and cofounder title for first hire?",
                    "type" => "story"
                ]
            ],
            [
                [
                    "by" => "cowile2",
                    "descendants" => 19,
                    "id" => 15709671,
                    "kids" => [ 15710444, 15710609, 15710463, 15710682, 15710313 ],
                    "score" => 72,
                    "time" => 1510796405,
                    "title" => "Show HN: Forsh – A Unix shell embedded in Forth",
                    "type" => "story",
                    "url" => "https://bitbucket.org/cowile/forsh"
                ]
            ],
            [
                [
                    "by" => "zthoutt",
                    "descendants" => 0,
                    "id" => 15706689,
                    "score" => 7,
                    "time" => 1510772353,
                    "title" => "Show HN: AI Consulting Contract",
                    "type" => "story",
                    "url" => "https://www.github.com/zackthoutt/ai-consulting"
                ]
            ],
            [
                [
                    "by" => "adamnemecek",
                    "descendants" => 41,
                    "id" => 15697697,
                    "kids" => [ 15702067, 15700769, 15700800, 15702983, 15700592 ],
                    "score" => 84,
                    "time" => 1510683738,
                    "title" => "Scripting the Haiku GUI with hey",
                    "type" => "story",
                    "url" => "https://www.haiku-os.org/blog/"
                    . "humdinger/2017-11-05_scripting_the_gui_with_hey/"
                ]
            ],
            [
                [
                    "by" => "TheAceOfHearts",
                    "id" => 15702067,
                    "kids" => [ 15702879, 15703398 ],
                    "parent" => 15697697,
                    "text" => "I haven&#x27;t tried BeOS&#x2F;HaikuOS or the...",
                    "time" => 1510730969,
                    "type" => "comment"
                ]
            ],

        ];
    }


    /**
     * Test add children
     */
    public function testChildren()
    {
        $node = new ItemNode([
            'id' => 23412343421,
            'by' => 'asdfsdfa',
            'time' => 1510796405,
            'type' => 'story'
        ]);
        $child1 = new ItemNode([
            'id' => 23412343421,
            'by' => 'asdfsdfa',
            'time' => 1510772353,
            'type' => 'comment'
        ]);
        $child2 = new ItemNode([
            'id' => 23412343421,
            'by' => 'asdfsdfa',
            'time' => 1510683738,
            'type' => 'comment'
        ]);

        $this->assertSame($node->getChildren(), []);
        $this->assertSame($node->countNodes(), 1);

        $node->addChild($child1);
        $this->assertSame($node->getChildren(), [$child1]);
        $this->assertSame($node->countNodes(), 2);

        $node->addChild($child2);
        $this->assertSame($node->getChildren(), [$child1, $child2]);
        $this->assertSame($node->countNodes(), 3);
    }
}
