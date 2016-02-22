<?php

/*
 * This file is part of the SimpleRouting package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Szenis\Parsers;

use Szenis\Interfaces\UrlParserInterface;

/**
 * Url parser 
 */
class UrlParser implements UrlParserInterface
{
	private $urlRequirements = [
		'n:' => '[0-9]++',			// numeric - only numbers
		'a:' => '[a-zA-Z]++',		// alphabetic - only alphabetic chars
		'an:' => '[0-9a-zA-Z]++',	// alphanumeric - match alphabetic and numeric chars
		'w:' => '[0-9a-zA-Z-_]++',	// word - matches alphanumeric, underscore and dash
	];

	/**
	 * Transform the url to a regex url
	 *
	 * @param  string $url
	 *
	 * @return string
	 */
	public function parse($url)
	{
		// remove trailing and leading slash
		$requestSegments = trim(strtolower($url), '/');
		// make an array from the segments
		$requestSegments = explode('/', $requestSegments, PHP_URL_PATH);
		// set url to an empty string
		$url = '';

		// loop trough all the segments
		foreach ($requestSegments as $segment) {
			// if the segment is an placeholder
			if ($this->isPlaceholder($segment)) {
				// get regex for the placeholder 
				$segment = $this->getRegexBySegmentRequirement($segment);
			}

			// add the new segment to the url + a slash
			$url .= $segment.'\/';
		}

		// return the new url but trim the last trailing slash
		return trim(strtolower($url), '\/');
	}

	/**
	 * Get the index numbers of the arguments
	 * 
	 * Consider the following url: offers/{category}/{product}
	 * offers has index 0 but is not an argument
	 * {category} has index 1 and is an arugment
	 * {product} has index 2 and is an argument
	 * 
	 * The array will contain the numbers 1 and 2
	 * 
	 * @return array
	 */
	public function getArgumentIndexes($url)
	{
		// remove trailing and leading slash
		$requestSegments = trim(strtolower($url), '/');
		// make an array from the segments
		$requestSegments = explode('/', $requestSegments, PHP_URL_PATH);

		$arguments = [];

		foreach ($requestSegments as $key => $segment) {
			if ($this->isPlaceholder($segment)) {
				$arguments[] = $key;
			}
		}

		return $arguments;
	}

	/**
	 * Check if the given placeholder has an unique requirement and return the regular expression
	 * For example numbers only returns [0-9]+ if no special requirement is given return default regex
	 *
	 * @param  string $segment
	 *
	 * @return string 
	 */
	private function getRegexBySegmentRequirement($segment)
	{
		// loop trough all the defined requirements
		foreach ($this->urlRequirements as $identifier => $regex) {
			$identifierLength = strlen($identifier);

			// if the length of the identiefier + the bracket is larger or equals to the length of the segment
			if ($identifierLength + 1 >= strlen($segment)) {
				continue;
			}

			// if the current requirement matches the begining of the segment
			if (substr($segment, 1, $identifierLength) === $identifier) {
				// return the special regex
				return $regex;
			}
		}

		// match anything till the next slash
		return '[^\/]++';
	}

	/**
	 * Check if an url segment is an placeholder
	 *
	 * @param  string $segment
	 *
	 * @return boolean
	 */
	private function isPlaceholder($segment)
	{
		return (substr($segment, 0, strlen('{')) === '{' && substr($segment, strlen($segment)-1) === '}');
	}
}
