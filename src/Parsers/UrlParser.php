<?php

namespace Szenis\Parsers;

class UrlParser
{
	/**
	 * Transform the url to a regex url
	 *
	 * @param  string $url
	 *
	 * @return string
	 */
	public function getRegexUrl($url)
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
				// match anything till the next slash
				$segment = '[^\/]+';
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
	 * Check if an url segment is an placeholder
	 *
	 * @param  string $segment
	 *
	 * @return boolean
	 */
	private function isPlaceholder($segment) {
		return (substr($segment, 0, strlen('{')) === '{' && substr($segment, strlen($segment)-1) === '}');
	}
}