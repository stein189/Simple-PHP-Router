<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Szenis;

use Szenis\Interfaces\RouteFactoryInterface;
use Szenis\Route;

/**
 * Route builder
 */
class RouteFactory implements RouteFactoryInterface
{
	/**
	 * Patterns that should be replaced
	 *
	 * @var array
	 */
	private $patterns = [
		'~/~',			     // slash
		'~{an:[^\/]+}~',     // placeholder accepts alphabetic and numeric chars
		'~{n:[^\/]+}~',      // placeholder accepts only numeric
		'~{a:[^\/]+}~',      // placeholder accepts only alphabetic chars
		'~{w:[^\/]+}~',      // placeholder accepts alphanumeric and underscore
		'~{\*:[^\/]+}~',     // placeholder match rest of url
		'~\\\/{\?:[^\/]+}~', // optional placeholder
		'~{[^\/]+}~',	     // normal placeholder
	];

	/**
	 * Replacements for the patterns index should be in sink
	 *
	 * @var array
	 */
	private $replacements = [
		'\/', 			     // slash
		'([0-9a-zA-Z]++)',   // placeholder accepts alphabetic and numeric chars
		'([0-9]++)',		 // placeholder accepts only numeric
		'([a-zA-Z]++)',	     // placeholder accepts only alphabetic chars
		'([0-9a-zA-Z-_]++)', // placeholder accepts alphanumeric and underscore
		'(.++)',			 // placeholder match rest of url
		'\/?([^\/]*)',	     // optional placeholder
		'([^\/]++)',	 	 // normal placeholder
	];

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
		return new Route($this->parseUrl($url), $this->parseMethod($method), $action);
	}

	/**
	 * Parse url into a regex url
	 *
	 * @param  string $url
	 *
	 * @return string
	 */
	private function parseUrl($url)
	{
		$newUrl = preg_replace($this->patterns, $this->replacements, $url);

		$newUrl = trim($newUrl, '\/');

		return $newUrl;
	}

	/**
	 * Create an array from the method(s)
	 *
	 * @param  string $method
	 * 
	 * @return array
	 */
	private function parseMethod($method)
	{
		return explode('|', $method);
	}
}
