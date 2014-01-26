<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IMessage extends IBasicMessage
{
	/**
	 * @return string
	 */
	function getHtml();


	/**
	 * @return string
	 */
	function getText();
}
