<?php

declare(strict_types=1);

namespace Tests\Resources\Controls;

use Tests\Resources\TestClass;


class WelcomeControl
{
	private TestClass $testClass;


	public function __construct(TestClass $testClass)
	{
		$this->testClass = $testClass;
	}


	public function hello(): string
	{
		return $this->testClass->getName();
	}
}
