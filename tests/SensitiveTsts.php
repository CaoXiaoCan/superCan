<?php
// +----------------------------------------------------------------------------
// | ThinkPHP [ When feeling bored, write some public methods ]
// +----------------------------------------------------------------------------
// | I wrote it solely for my own use. Feel free to use it if you find it useful
// +----------------------------------------------------------------------------
// | Author: caocan <353332645@qq.com>
// +----------------------------------------------------------------------------
require '../vendor/autoload.php';
use  can\superCan\sensitive\Sensitive;
$sensitive = new Sensitive();

$result = $sensitive
	->load(['傻瓜', '坏蛋'])
	->text('你是大傻瓜和坏蛋');

$has = $result->check(); // true
$clean = $result->filter(); // 你是大***和***

echo $has ? '命中敏感词' : '没有敏感词';
echo "\n清理后：" . $clean;