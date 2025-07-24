<?php
// +----------------------------------------------------------------------------
// | ThinkPHP [ When feeling bored, write some public methods ]
// +----------------------------------------------------------------------------
// | I wrote it solely for my own use. Feel free to use it if you find it useful
// +----------------------------------------------------------------------------
// | Author: caocan <353332645@qq.com>
// +----------------------------------------------------------------------------

namespace  can\superCan\stringhelper;
class StringHelper
{
	/**
	 * 将驼峰命名的字符串转换为下划线命名的字符串
	 *
	 * @param string $input 驼峰命名的字符串
	 * @return string 下划线命名的字符串
	 */
	public static function camelToSnake(string $input): string
	{
		return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($input)));
	}

	/**
	 * 将下划线命名的字符串转换为驼峰命名的字符串
	 *
	 * @param string $input 下划线命名的字符串
	 * @return string 驼峰命名的字符串
	 */
	public static function snakeToCamel(string $input): string
	{
		return lcfirst(str_replace('_', '', ucwords($input, '_')));
	}

	/**
	 * 生成指定长度的随机字符串
	 *
	 * @param int $length 随机字符串的长度，默认为16
	 * @param int $type 是否包含大小写 默认为1  1=纯数字 2=纯字母 3=纯小写字母  4=纯大写字母 5=纯数字加小写字母  6=纯数字加大写字母
	 * @return string 生成的随机字符串
	 */
	public static function random(int $length = 16, int $type=1): string
	{
		$string = match ($type) {
			'1' => '0123456789',
			'2' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'3' => 'abcdefghijklmnopqrstuvwxyz',
			'4' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'5' => '0123456789abcdefghijklmnopqrstuvwxyz',
			'6' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			default => '0123456789',
		};
		return substr(str_shuffle(str_repeat($string, 5)), 0, $length);
	}

	/**
	 * 截取字符串并在末尾添加省略号，如果字符串长度未超过指定长度，则不添加省略号
	 * 兼容多字节字符
	 *
	 * @param string $text 要截取的字符串
	 * @param int $length 截取后的字符串长度，默认为20
	 * @param string $suffix 添加到截取后字符串末尾的省略号，默认为'...'
	 * @return string 截取后的字符串
	 */
	public static function truncate(string $text, int $length = 20, string $suffix = '...'): string
	{
		return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . $suffix : $text;
	}


	/**
	 * 遮掩字符串信息
	 * 该方法用于遮掩字符串的中间部分，常用于隐藏敏感信息
	 * 根据字符串长度的不同，遮掩的策略也不同
	 *
	 * @param mixed $str 待处理的字符串
	 * @return string 处理后的字符串，部分信息被遮掩
	 */
	public static function coverString($str) {
		// 检查输入是否为字符串，若非字符串则返回空字符串
		if (!is_string($str)) {
			return '';
		}

		// 设置内部编码为UTF-8，以正确处理多字节字符
		mb_internal_encoding('UTF-8');

		// 获取字符串长度
		$length = mb_strlen($str);

		// 计算起始位置，确保至少有两个字符不被遮掩
		$startPos = max(0, $length - 2);

		// 根据字符串长度选择不同的遮掩策略
		if ($length <= 2) {
			// 字符串长度小于等于2时，只保留第一个字符
			return mb_substr($str, 0, 1) . '***';
		} elseif ($length <= 4) {
			// 长度为3或4时，保留前两个字符
			return mb_substr($str, 0, 2) . '***';
		} else {
			// 长度大于4时，保留首尾各两个字符
			return mb_substr($str, 0, 2) . '***' . mb_substr($str, $startPos, 2);
		}
	}
	// 手机号脱敏
	/**
	 * 对手机号进行脱敏处理，隐藏中间四位数字
	 *
	 * @param string $phone 手机号
	 * @return string 脱敏后的手机号
	 */
	public static function maskPhone(string $phone): string
	{
		return preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $phone);
	}

	/**
	 * 检查字符串是否以指定前缀开始
	 *
	 * @param string $haystack 要检查的字符串
	 * @param string $needle 指定的前缀
	 * @return bool 如果字符串以指定前缀开始则返回true，否则返回false
	 */
	public static function startsWith(string $haystack, string $needle): bool
	{
		return str_starts_with($haystack, $needle);
	}

	/**
	 * 检查字符串是否以指定后缀结束
	 *
	 * @param string $haystack 要检查的字符串
	 * @param string $needle 指定的后缀
	 * @return bool 如果字符串以指定后缀结束则返回true，否则返回false
	 */
	public static function endsWith(string $haystack, string $needle): bool
	{
		return str_ends_with($haystack, $needle);
	}
}