<?php

declare(strict_types=1);

namespace Tests\Resources;


class TestClass
{
	private string $name;


	public function setName(string $name): void
	{
		$this->name = $name;
	}


	public function getName(): string
	{
		return $this->name;
	}
}
