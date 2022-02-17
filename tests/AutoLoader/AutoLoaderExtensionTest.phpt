<?php

declare(strict_types=1);

namespace Tests\AutoLoader;

use Nette;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Tester\Assert;
use Tester\TestCase;
use Tests\Resources\Controls\WelcomeControl;
use Tests\Resources\Forms\TestFormFactory;
use Tests\Resources\Orm;
use Tests\Resources\TestClass;
use Tests\Resources\Users\User;
use Tests\Resources\Users\UserType;

require __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class AutoLoaderExtensionTest extends TestCase
{
	private Nette\DI\Container $container;


	public function setUp(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->addConfig(__DIR__ . '/../config/config.neon');

		$configurator->setTempDirectory(__DIR__ . '/../temp');
		$this->container = $configurator->createContainer();
	}

	public function testAutoLoadingControl(): void
	{
		$control = $this->container->getByType(WelcomeControl::class);
		Assert::equal(WelcomeControl::class, get_class($control));

		if($control instanceof WelcomeControl) {
			$testClass = $this->container->getByType(TestClass::class);
			if($testClass instanceof TestClass) {
				$testClass->setName('John Doe');
			}

			Assert::equal('John Doe', $control->hello());
		}
	}


	public function testAutoLoadingForm(): void
	{
		$form = $this->container->getByType(TestFormFactory::class);
		Assert::equal(TestFormFactory::class, get_class($form));

		if($form instanceof TestFormFactory) {
			Assert::true($form->create());
		}
	}
}

(new AutoLoaderExtensionTest())->run();
