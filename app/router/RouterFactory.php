<?php

namespace greeny\Website\Routing;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @param $debugMode
	 * @return RouteList
	 */
	public function createRouter($debugMode)
	{
		$router = new RouteList();

		return $debugMode ? $this->createLocalRouter($router) : $this->createProductionRouter($router);
	}

	protected function createLocalRouter($router)
	{
		$router[] = new Route('blog/compose', array(
			'module' => 'Blog',
			'presenter' => 'Article',
			'action' => 'compose',
		));
		$router[] = new Route('blog/<slug>[/<action>]', array(
			'module' => 'Blog',
			'presenter' => 'Article',
			'action' => 'detail',
			'slug' => NULL,
		));
		$router[] = new Route('projects/new', array(
			'module' => 'Projects',
			'presenter' => 'Project',
			'action' => 'new',
		));
		$router[] = new Route('projects/<slug>[/<action>[/<id>]]', array(
			'module' => 'Projects',
			'presenter' => 'Project',
			'action' => 'detail',
			'slug' => NULL,
		));
		$router[] = new Route('<module>/<presenter>/<action>[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Dashboard',
			'action' => 'default',
		));
		return $router;
	}

	protected function createProductionRouter($router)
	{
		$router[] = new Route('//blog.blatny.org/compose', array(
			'module' => 'Blog',
			'presenter' => 'Article',
			'action' => 'compose',
		));
		$router[] = new Route('//blog.blatny.org/<slug>[/<action>]', array(
			'module' => 'Blog',
			'presenter' => 'Article',
			'action' => 'detail',
			'slug' => NULL,
		));
		$router[] = new Route('//projects.blatny.org/new', array(
			'module' => 'Projects',
			'presenter' => 'Project',
			'action' => 'new',
		));
		$router[] = new Route('//projects.blatny.org/<slug>[/<action>[/<id>]]', array(
			'module' => 'Projects',
			'presenter' => 'Project',
			'action' => 'detail',
			'slug' => NULL,
		));
		$router[] = new Route('//[<module>.]blatny.org/<presenter>/<action>[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Dashboard',
			'action' => 'default',
		));
		return $router;
	}
}
