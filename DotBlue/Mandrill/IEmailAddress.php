<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IEmailAddress
{
	/**
	 * @return string
	 */
	function getEmail();


	/**
	 * @return string
	 */
	function getName();
}
