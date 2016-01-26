<?php

namespace Szenis\Validators;

/**
 * ArgumentsValidator
 */
class ArgumentsValidator
{
	/**
	 * Validate the given url
	 *
	 * @param  string $url
	 */
	public function validateUrl($url)
	{
		// if no valid url is given throw an exception
		if ($url === null || $url === '') {
			throw new \Exception('No url provided, for root use /');
		}
	}

	/**
	 * Validate the given arguments
	 *
	 * @param  array $arguments
	 */
	public function validateArguments($arguments)
	{
		// if the method is not set throw an exception
		if (!isset($arguments['method'])) {
			throw new \Exception('Request method argument is missing for route: '.$url);
		}

		// if the method arguments is not an array throw an exception
		if (!is_array($arguments['method'])) {
			throw new \Exception('Request method should be an array for route: '.$url);
		}		
		
		// if the class name is not provided throw exception
		if (!isset($arguments['class'])) {
			throw new \Exception('Class argument is missing');
		}

		// if the function argument is not provided throw an exception
		if (!isset($arguments['function'])) {
			throw new \Exception('Function argument is missing');
		}
		
		// if the class argument is not provided throw an exception
		if (!class_exists($arguments['class'])) {
			throw new \Exception('Class '.$arguments['class'].' not found!');
		}
		
		// get all the methods from the given class
		$classMethods = get_class_methods($arguments['class']);
		
		// check if the given method exists in class, if not throw exception
		if (!in_array($arguments['function'], $classMethods)) {
			throw new \Exception('Function '.$arguments['function'].' does not exists in class '.$arguments['class']);
		}
	}
}