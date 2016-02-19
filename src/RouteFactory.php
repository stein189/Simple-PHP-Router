<?php

namespace Szenis;

use Szenis\Interfaces\RouteFactoryInterface;
use Szenis\Interfaces\RouteInterface;
use Szenis\Interfaces\ValidatorInterface;
use Szenis\Interfaces\UrlParserInterface;

/**
 * Route builder
 */
class RouteFactory implements RouteFactoryInterface
{
	/**
	 * @var ValidatorInterface
	 */
	private $validator;

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
	public function __construct(ValidatorInterface $validator, UrlParserInterface $parser)
	{
		$this->validator = $validator;
		$this->parser = $parser;
	}

	/**
	 * Create new route
	 *
	 * @param  RouteInterface $route
	 * @param  string         $url
	 * @param  string         $method
	 * @param  string         $action
	 *
	 * @return RouteInterface
	 */
	public function create(RouteInterface $route, $url, $method, $action)
	{
		$url  = preg_replace('/\?.*/', '', $url);

		$this->validator->validateUrl($url);
		$this->validator->validateMethod($method);
		$this->validator->validateAction($action);

		$route->setUrl($this->parser->parse($url));
		$route->setMethod($this->parseToArray($method));
		$route->setAction($action);
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
