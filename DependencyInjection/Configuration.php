<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 30/05/2018
 */

namespace Octopouce\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('octopouce_blog');

		$rootNode
			->children()
				->booleanNode('enabled')->defaultTrue()->end()
				->scalarNode('upload_path')->defaultValue('uploads/blog')->end()
			->end()
		;

		return $treeBuilder;
	}
}