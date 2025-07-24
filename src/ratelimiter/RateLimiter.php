<?php
// +----------------------------------------------------------------------------
// | ThinkPHP [ When feeling bored, write some public methods ]
// +----------------------------------------------------------------------------
// | I wrote it solely for my own use. Feel free to use it if you find it useful
// +----------------------------------------------------------------------------
// | Author: caocan <353332645@qq.com>
// +----------------------------------------------------------------------------
namespace can\superCan\rateLimiter;
use Predis\Client;

/**
 * RateLimiter类用于实现基于Redis的速率限制功能
 * 它通过跟踪特定键在指定时间窗口内的请求次数来限制API的访问频率
 */
class RateLimiter
{
	/**
	 * Redis客户端实例，用于与Redis数据库进行交互
	 */
	protected Client $redis;

	/**
	 * 最大请求数，表示在时间窗口内允许的最大请求次数
	 */
	protected int $maxRequests;

	/**
	 * 时间窗口的秒数，表示速率限制的时间范围
	 */
	protected int $windowSeconds;

	/**
	 * 构造函数，初始化RateLimiter类的实例
	 *
	 * @param array $redisConfig Redis连接配置数组，包括host、port和database等信息
	 * @param int $maxRequests 最大请求数，默认为100
	 * @param int $windowSeconds 时间窗口的秒数，默认为60秒
	 */
	public function __construct(array $redisConfig = [], int $maxRequests = 100, int $windowSeconds = 60)
	{
		// 初始化Redis客户端，使用提供的配置或默认值
		$this->redis = new Client([
			'scheme'   => 'tcp',
			'host'     => $redisConfig['host'] ?? '127.0.0.1',
			'port'     => $redisConfig['port'] ?? 6379,
			'database' => $redisConfig['database'] ?? 0,
		]);

		// 设置最大请求数和时间窗口的秒数
		$this->maxRequests   = $maxRequests;
		$this->windowSeconds = $windowSeconds;
	}

	/**
	 * 检查给定键是否允许进行下一次请求
	 *
	 * @param string $key 用于标识请求者的键，通常是用户ID或IP地址
	 * @return bool 如果允许请求，则返回true；否则返回false
	 */
	public function allow(string $key): bool
	{
		// 构造Redis中的键名
		$redisKey = "ratelimit:" . $key;

		// 增加键对应的计数器
		$current = $this->redis->incr($redisKey);

		// 如果当前计数器为1，说明这是时间窗口内的第一次请求，设置过期时间
		if ($current === 1) {
			$this->redis->expire($redisKey, $this->windowSeconds);
		}

		// 返回是否允许请求
		return $current <= $this->maxRequests;
	}

	/**
	 * 获取给定键剩余的请求数
	 *
	 * @param string $key 用于标识请求者的键，通常是用户ID或IP地址
	 * @return int 剩余的请求数
	 */
	public function remaining(string $key): int
	{
		// 构造Redis中的键名
		$redisKey = "ratelimit:" . $key;

		// 获取当前键对应的计数器值
		$current = $this->redis->get($redisKey);

		// 计算并返回剩余的请求数
		return $current === null ? $this->maxRequests : max(0, $this->maxRequests - $current);
	}

	/**
	 * 重置给定键的请求计数器
	 *
	 * @param string $key 用于标识请求者的键，通常是用户ID或IP地址
	 */
	public function reset(string $key): void
	{
		// 构造Redis中的键名
		$redisKey = "ratelimit:" . $key;

		// 删除键对应的计数器，即重置速率限制
		$this->redis->del($redisKey);
	}
}