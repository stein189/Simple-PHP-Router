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
	 * @param  array          $arguments
	 *
	 * @return RouteInterface
	 */
	public function create(RouteInterface $route, $url, arguments)
	{
		$this->validator->validateUrl($url);
		$this->validator->validateArguments($arguments);

		$route->setUrl($this->parser->parse($url));
		$route->setMethod($arguments['method']);
		$route->setClass($arguments['class']);
		$route->setFunction($arguments['function']);
		$route->setArgumentIndexes($this->parser->getArgumentIndexes($url));

		return $route;
	}
}