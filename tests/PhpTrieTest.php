<?php

use awheel\PhpTrie\PhpTrie;

class PhpTrieTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PhpTrie
     */
    protected $trie;

    /**
     * @var
     */
    protected $string;

    public function setUp()
    {
        $this->string = '我们是违禁词: abcde';
        $this->trie = new PhpTrie();
        $this->trie->addWord($words = ['我是', '我们', 'abcd']);
    }

    public function testTrie()
    {
        $this->assertTrue(is_object($this->trie));
    }

    public function testAddWord()
    {
        $this->assertCount(2, $this->trie->getTrie());
    }

    public function testCheck()
    {
        $this->assertTrue($this->trie->check($this->string));
    }

    public function testWord()
    {
        $this->assertTrue($this->trie->isWord('我们'));
    }

    public function testSearch()
    {
        $words = $this->trie->search($this->string);
        $this->assertCount(2, $words);
    }

    public function testReplace()
    {
        $this->assertTrue($this->trie->replace($this->string) == '是违禁词: e');
    }
}
