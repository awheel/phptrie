<?php

namespace awheel\PhpTrie;

/**
 * Trie 树实现的违禁词搜索和替换
 *
 * @package library
 */
class PhpTrie
{
    /**
     * trie 树
     *
     * @var array
     */
    private $trie = [];

    /**
     * 添加违禁词
     *
     * @param  string|array $word
     *
     * @return bool
     */
    function addWord($word)
    {
        if (is_array($word)) {
            array_map([$this, 'addWord'], $word);
            return true;
        }

        $node = &$this->trie;
        $length = mb_strlen($word);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($word, $i, 1);
            if (!isset($node[$char])) {
                $node[$char]['end'] = false;
            }
            if ($i == $length - 1) {
                $node[$char]['end'] = true;
            }

            $node = &$node[$char];
        }

        return true;
    }

    /**
     * 判断字符串是否在 trie 树内
     *
     * @param $text
     *
     * @return bool
     */
    function isWord($text)
    {
        $node = &$this->trie;
        $length = mb_strlen($text);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if (! isset($node[$char])) {
                return false;
            }
            else {
                if ($i == ($length - 1) && $node[$char]['end'] == true) {
                    return true;
                }
                elseif ($i == ($length - 1) && $node[$char]['end'] == false) {
                    return false;
                }
                $node = &$node[$char];
            }
        }

        return false;
    }

    /**
     * 搜索字符串内的全部违禁词
     *
     * @param $text
     *
     * @return array
     */
    function search($text)
    {
        $length = mb_strlen($text);
        $node = $this->trie;
        $find = [];
        $position = 0;
        $parent = false;
        $word = '';
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if (isset($node[$char])) {
                $word = $word . $char;
                $node = $node[$char];
                if ($parent == false) {
                    $position = $i;
                }
                $parent = true;
                if ($node['end']) {
                    $find[] = ['position' => $position, 'word' => $word];
                }
            }
            else {
                $node = $this->trie;
                $word = '';
                if ($parent) {
                    $i = $i - 1;
                    $parent = false;
                }
            }
        }

        return $find;
    }

    /**
     * 判断字符串内是否有违禁词
     *
     * @param $text
     *
     * @return bool
     */
    function check($text)
    {
        $length = mb_strlen($text);
        $node = $this->trie;
        $parent = false;
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if (isset($node[$char])) {
                $node = $node[$char];
                $parent = true;
                if ($node['end']) {
                    return true;
                }
            }
            else {
                $node = $this->trie;
                if ($parent) {
                    $i = $i - 1;
                    $parent = false;
                }
            }
        }

        return false;
    }

    /**
     * 替换字符串内的全部违禁词, 有覆盖关系
     *
     * @param $text
     *
     * @return string
     */
    public function replace($text)
    {
        $length = mb_strlen($text);
        $node = $this->trie;
        $position = 0;
        $parent = false;
        $word = '';
        $deep = false;
        $find = [];
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if (isset($node[$char])) {
                $word = $word . $char;
                $node = $node[$char];
                if ($parent == false) {
                    $position = $i;
                }
                $parent = true;

                if ($node['end'] && !$deep) {
                    $index = array_search(mb_substr($word, 0, -1), array_column($find, 'word'));
                    if ($index !== false) {
                        unset($find[$index]);
                        $find = array_values($find);
                    }

                    $find[] = ['position' => $position, 'word' => $word];
                }
            }
            else {
                $node = $this->trie;
                $word = '';
                if ($parent) {
                    $i = $i - 1;
                    $parent = false;
                }
            }
        }

        krsort($find);
        foreach ($find as $item) {
            $text = mb_substr($text, 0, $item['position']).mb_substr($text, $item['position']+mb_strlen($item['word']));
        }

        return $text;
    }

    /**
     * 获取 trie 示例
     *
     * @return static
     */
    static public function getInstance()
    {
        return new static();
    }

    /**
     * 获取 trie 树
     *
     * @return array
     */
    public function getTrie()
    {
        return $this->trie;
    }

    /**
     * 设置 trie 树
     *
     * @param $trie
     */
    public function setTrie($trie)
    {
        $this->trie = $trie;
    }
}
