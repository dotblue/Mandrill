<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill\Exporters;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IMessageExporter
{
	/**
	 * @param \DotBlue\Mandrill\IMessage $message
	 * @return array
	 */
	function message(\DotBlue\Mandrill\IMessage $message);


	/**
	 * @param \DotBlue\Mandrill\ITemplateMessage $message
	 * @return array
	 */
	function templateMessage(\DotBlue\Mandrill\ITemplateMessage $message);
}
