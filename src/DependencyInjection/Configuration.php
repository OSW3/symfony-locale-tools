<?php 
namespace OSW3\LocaleTools\DependencyInjection;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	/**
	 * define the name of the configuration tree.
	 * > /config/packages/locale_tools.yaml
	 *
	 * @var string
	 */
	public const NAME = "locale_tools";

	/**
	 * Update and return the Configuration Builder
	 *
	 * @return TreeBuilder
	 */
	public function getConfigTreeBuilder(): TreeBuilder
    {
        $definition = require Path::join(__DIR__, "../../", "config/definition/definition.php");
        $builder    = new TreeBuilder( static::NAME );

		return $builder;
    }
}