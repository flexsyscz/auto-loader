<?php

declare(strict_types=1);

namespace Flexsyscz\AutoLoader;

use Flexsyscz\Environment\TempStorage;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\DI\Extensions\InjectExtension;
use Nette\Loaders\RobotLoader;
use Nette\Utils\Strings;


class AutoLoaderExtension extends CompilerExtension
{
	public function getConfigSchema(): Nette\Schema\Schema
	{
		return Nette\Schema\Expect::arrayOf(Nette\Schema\Expect::structure([
			'path' => Nette\Schema\Expect::string()->required(),
			'allow' => Nette\Schema\Expect::anyOf(Nette\Schema\Expect::string(), Nette\Schema\Expect::arrayOf('string')),
			'ignore' => Nette\Schema\Expect::anyOf(Nette\Schema\Expect::string(), Nette\Schema\Expect::arrayOf('string')),
		]), 'string');
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		if (!isset($builder->parameters['tempDir'])) {
			throw new InvalidStateException('AutoLoader extension requires path to the temp directory.');
		}

		$loader = new RobotLoader;
		$loader->setTempDirectory(TempStorage::getDirectory($builder->parameters['tempDir'], 'auto-loader'))
			->setAutoRefresh();

		$json = json_encode($this->config);
		if(!$json) {
			throw new InvalidStateException('AutoLoader config is not valid.');
		}

		$config = json_decode($json, true);
		if(!is_iterable($config)) {
			throw new InvalidStateException('AutoLoader config is not valid.');
		}

		foreach ($config as $autoLoader) {
			$loader->addDirectory($autoLoader['path']);
		}

		foreach ($loader->getIndexedClasses() as $className => $classPath) {
			foreach ($config as $prefix => $autoLoader) {
				$path = realpath(Nette\Utils\FileSystem::normalizePath($autoLoader['path']));
				if (preg_match("#^{$path}#", $classPath)) {
					$checked = true;

					if (isset($autoLoader['allow'])) {
						$checked = false;
						foreach ((array) $autoLoader['allow'] as $allow) {
							if (preg_match("#{$allow}#", $className)) {
								$checked = true;
								break;
							}
						}
					}

					if (isset($autoLoader['ignore'])) {
						foreach ((array) $autoLoader['ignore'] as $ignore) {
							if (preg_match("#{$ignore}#", $className)) {
								$checked = false;
								break;
							}
						}
					}

					if($checked) {
						$name = explode('\\', $className);
						$builder->addDefinition($this->prefix(sprintf('%s.%s', $prefix, Strings::firstLower(array_pop($name)))))
							->setFactory($className)
							->addTag(InjectExtension::TagInject);
					}
				}
			}
		}
	}
}
