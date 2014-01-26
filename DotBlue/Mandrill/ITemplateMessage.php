<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface ITemplateMessage extends IBasicMessage
{
	/**
	 * @return string
	 */
	function getTemplateName();


	/**
	 * $name => $value
	 *
	 * @return string[]
	 */
	function getEditableRegions();
}
