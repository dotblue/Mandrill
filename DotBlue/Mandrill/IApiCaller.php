<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IApiCaller
{
	/**
	 * @param string $url
	 * @param array $parameters
	 * @return mixed
	 */
	function call($url, array $parameters);
}
