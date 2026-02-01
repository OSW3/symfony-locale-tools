<?php 
namespace OSW3\LocaleTools\DependencyInjection;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Config\FileLocator;
use OSW3\LocaleTools\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class LocaleToolsExtension extends Extension 
{
	/**
	 * Bundle configuration Injection
	 *
	 * @param array $configs
	 * @param ContainerBuilder $container
	 *
	 * @return void
	 */
	public function load(array $configs, ContainerBuilder $container): void
    {
		// Default Config
		// --
		
		$config = $this->processConfiguration(new Configuration(), $configs);
		$container->setParameter($this->getAlias(), $config);		
        

		// Bundle config location
		// --
		
		$locator = new FileLocator(Path::join(__DIR__, "/../../", "config"));
		$loader = new YamlFileLoader($container, $locator);
		

		// Services Injection
		// --
		
		$loader->load('services.yaml');
    }
}