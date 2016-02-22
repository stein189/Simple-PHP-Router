<?php

namespace Szenis;

use Szenis\Interfaces\RouteFactoryInterface;
use Szenis\Interfaces\UrlParserInterface;
use Szenis\Route;

/**
 * Route builder
 */
class RouteFactory implements RouteFactoryInterface
{
	/**
	 * @var UrlParserInterface
	 */
	private $parser;

	/**
	 * Construct the route factory
	 *
	 * @param ValidatorInterface $validator
	 * @param UrlParserInterface $parser
	 */
	public function __construct(UrlParserInterface $parser)
	{
		$this->parser = $parser;
	}

	/**
	 * Create new route
	 *
	 * @param  string         $url
	 * @param  string         $method
	 * @param  string         $action
	 *
	 * @return RouteInterface
	 */
	public function create($url, $method, $action)
	{
		$route = new Route($this->parser->parse($url), $this->parseToArray($method), $action);

		$route->setArgumentIndexes($this->parser->getArgumentIndexes($url));

		return $route;
	}

	/**
	 * Create an array from the method(s)
	 *
	 * @param  string $method
	 * 
	 * @return array
	 */
	private function parseToArray($method)
	{
		return explode('|', $method);
	}
}
