<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class Message extends AbstractMessage implements IMessage
{
	/** @var string */
	public $html;

	/** @var string */
	public $text;


	/**
	 * @return string
	 */
	public function getHtml()
	{
		return $this->html;
	}


	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}
}
