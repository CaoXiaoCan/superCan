<?php
// +----------------------------------------------------------------------------
// | ThinkPHP [ When feeling bored, write some public methods ]
// +----------------------------------------------------------------------------
// | I wrote it solely for my own use. Feel free to use it if you find it useful
// +----------------------------------------------------------------------------
// | Author: caocan <353332645@qq.com>
// +----------------------------------------------------------------------------

namespace can\superCan\sensitive;

/**
 * Sensitive类用于敏感词检测和过滤
 */
class Sensitive
{
	// 保存敏感词列表
	protected array $wordList = [];
	// 保存待检测的文本
	protected string $text = '';

	/**
	 * 设置敏感词
	 *
	 * @param array $words 敏感词数组
	 * @return static 返回当前实例，支持链式调用
	 */
	public function load(array $words): static
	{
		$this->wordList = $words;
		return $this;
	}

	/**
	 * 设置要检测的文本
	 *
	 * @param string $text 待检测的文本
	 * @return static 返回当前实例，支持链式调用
	 */
	public function text(string $text): static
	{
		$this->text = $text;
		return $this;
	}

	/**
	 * 检测是否包含敏感词
	 *
	 * @return bool 如果包含敏感词返回true，否则返回false
	 */
	public function check(): bool
	{
		foreach ($this->wordList as $word) {
			if (stripos($this->text, $word) !== false) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 过滤敏感词
	 *
	 * @param string $replace 替换字符串，默认为'*'
	 * @return string 过滤后的文本
	 */
	public function filter(string $replace = '*'): string
	{
		$text = $this->text;
		foreach ($this->wordList as $word) {
			$text = str_ireplace($word, str_repeat($replace, mb_strlen($word)), $text);
		}
		return $text;
	}
}