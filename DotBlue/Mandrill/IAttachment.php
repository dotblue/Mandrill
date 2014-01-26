<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IAttachment
{
	/**
	 * @return string
	 */
	function getName();


	/**
	 * @return string
	 */
	function getType();


	/**
	 * @return string
	 */
	function getContent();
}
