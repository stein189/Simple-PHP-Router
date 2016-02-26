<?php

/*
 * This file is part of the Simple-PHP-Router package.
 *
 * (c) Stein Janssen <birdmage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RouterTests;

/**
 * Class is used by the RouteResolverTest
 */
class TestController
{
	/**
	 * return string to confirm that the action was called
	 *
	 * @return string
	 */
	public function index()
	{
		return 'index called';
	}
}