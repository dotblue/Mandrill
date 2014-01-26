<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class Attachment implements IAttachment
{
	/** @var string */
	private $type;

	/** @var string */
	private $name;

	/** @var string */
	private $content;


	/**
	 * @param string $name
	 * @param string $type
	 * @param string $content
	 */
	public function __construct($name, $type, $content)
	{
		$this->type = $type;
		$this->name = $name;
		$this->content = $content;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}


	/**
	 * @param string $path
	 * @param string $type
	 * @param string $name
	 * @return static
	 * @throws \RuntimeException
	 */
	public static function fromFile($path, $type, $name = NULL)
	{
		$fileContent = @file_get_contents($path); // intentionally @
		if ($fileContent === FALSE) {
			throw new \RuntimeException("File '$path' is not readable.'");
		}
		$content = base64_encode($fileContent);
		$name = $name ?: pathinfo($path, PATHINFO_BASENAME);
		return new static($name, $type, $content);
	}
}
