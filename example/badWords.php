<?php

$trie = new PhpTrie();
$words = ['我', '我们', 'abcd'];
$string = '我们是违禁词: abcde';

var_dump($trie->check($string), $trie->search($string), $trie->isWord('我们'));
