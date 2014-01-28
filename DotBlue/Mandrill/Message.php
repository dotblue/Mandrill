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
	 * @param string $html
	 * @return $this
	 */
	public function setHtml($html)
	{
		$this->html = $html;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}


	/**
	 * @param string $text
	 * @return $this
	 */
	public function setText($text)
	{
		$this->text = $text;
		return $this;
	}
}
