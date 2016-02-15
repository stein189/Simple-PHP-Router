<?php

namespace Szenis\Interfaces;

/**
 * UrlParserInterface
 */
interface UrlParserInterface
{
	/**
	 * Parse the url to a regex url
	 *
	 * @param  string $url
	 *
	 * @return string
	 */
	public function parse($url);

	/**
	 * Get the argument indexes
	 *
	 * @param  string $url
	 *
	 * @return array
	 */
	public function getArgumentIndexes($url);
}