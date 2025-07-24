<?php
namespace superCan\PreciseMath;

class PreciseCalculator
{
	protected $value;
	protected $scale;

	public function __construct(string $initialValue, int $scale)
	{
		$this->value = $initialValue;
		$this->scale = $scale;
	}

	public function add(string $operand): self
	{
		$this->value = bcadd($this->value, $operand, $this->scale);
		return $this;
	}

	public function sub(string $operand): self
	{
		$this->value = bcsub($this->value, $operand, $this->scale);
		return $this;
	}

	public function mul(string $operand): self
	{
		$this->value = bcmul($this->value, $operand, $this->scale);
		return $this;
	}

	public function div(string $operand): self
	{
		$this->value = bcdiv($this->value, $operand, $this->scale);
		return $this;
	}

	public function getResult(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}
}