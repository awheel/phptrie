# PhpTrie

Trie 树实现的违禁词搜索和替换

## 安装

```php
composer require awheel/phptrie
```

## 示例
````php

$trie = new PhpTrie();
$words = ['我是', '我们', 'abcd'];
$string = '我们是违禁词: abcde';

$trie->addWord($words);
var_dump($trie->check($string))
var_dump($trie->search($string))
var_dump($trie->isWord('我们')))

````
