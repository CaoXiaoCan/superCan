<?php
// +----------------------------------------------------------------------------
// | ThinkPHP [ When feeling bored, write some public methods ]
// +----------------------------------------------------------------------------
// | I wrote it solely for my own use. Feel free to use it if you find it useful
// +----------------------------------------------------------------------------
// | Author: caocan <353332645@qq.com>
// +----------------------------------------------------------------------------

require '../vendor/autoload.php';

use can\superCan\rateLimiter\RateLimiter;

$limiter = new RateLimiter([
	'host' => '127.0.0.1',
	'port' => 6379,
	'database' => 2, // 使用第 2 个 Redis 库
], 5, 60); // 60 秒内最多允许 5 次请求

$key = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

if (!$limiter->allow($key)) {
	http_response_code(429);
	exit('请求频率过高，请稍后再试。');
}

echo '请求成功，剩余次数：' . $limiter->remaining($key);