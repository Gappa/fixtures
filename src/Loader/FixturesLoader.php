<?php

namespace Nettrine\Fixtures\Loader;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Nette\DI\Container;
use Nettrine\Fixtures\ContainerAwareInterface;

class FixturesLoader extends Loader
{

	/** @var Container */
	private $container;

	/** @var string[] */
	private $paths;

	/**
	 * @param string[] $paths
	 * @param Container $container
	 */
	public function __construct(array $paths, Container $container)
	{
		$this->paths = $paths;
		$this->container = $container;
	}

	/**
	 * @param string[] $paths
	 * @return void
	 */
	public function loadPaths(array $paths)
	{
		foreach ($paths as $path) {
			if (is_dir($path)) {
				$this->loadFromDirectory($path);
			} elseif (is_file($path)) {
				$this->loadFromFile($path);
			}
		}
	}

	/**
	 * @return void
	 */
	public function load()
	{
		$this->loadPaths($this->paths);
	}

	/**
	 * @param FixtureInterface $fixture
	 * @return void
	 */
	public function addFixture(FixtureInterface $fixture)
	{
		if ($fixture instanceof ContainerAwareInterface) {
			$fixture->setContainer($this->container);
		}
		parent::addFixture($fixture);
	}

}