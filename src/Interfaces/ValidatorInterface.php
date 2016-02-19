<?php

namespace Szenis\Interfaces;

/**
 * Validator Interface
 */
interface ValidatorInterface
{
	/**
	 * Validate url
	 *
	 * @param  string $url
	 *
	 * @throws \Exception
	 */
	public function validateUrl($url);
}