<?php

/*
 * This file is part of the SimpleRouting package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Szenis\Validators;

use Szenis\Interfaces\ValidatorInterface;
use Szenis\Exceptions\InvalidArgumentException;

/**
 * ArgumentsValidator
 */
class ArgumentsValidator implements ValidatorInterface
{
	/**
	 * Allowed methods
	 * 
	 * @var array
	 */
	private $allowedMethods = [
		'GET',
		'POST',
		'PUT',
		'PATCH',
		'DELETE',
	];

	/**
	 * Validate the given url
	 *
	 * @param  string $url
	 */
	public function validateUrl($url)
	{
		// if no valid url is given throw an exception
		if ($url === null || $url === '') {
			throw new InvalidArgumentException('No url provided, for root use /');
		}
	}

	/**
	 * Validate the given method(s)
	 *
	 * @param  string $method
	 */
	public function validateMethod($method)
	{
		if ($method === null || $method === '') {
			throw new InvalidArgumentException('No method provided');
		}

		$methods = explode('|', $method);

		foreach ($methods as $method) {
			if (!in_array($method, $this->allowedMethods)) {
				throw new InvalidArgumentException('Method not allowed. allowed methods: GET, POST, PUT, PATCH, DELETE');
			}
		}
	}

	/**
	 * Validate action
	 * 
	 * @param string|function $action
	 */
	public function validateAction($action)
	{
		if (!(is_object($action) && ($action instanceof \Closure)) && empty($action)) {
			throw new InvalidArgumentException('Action should be a Closure or a path to a function');
		}
	}
}
