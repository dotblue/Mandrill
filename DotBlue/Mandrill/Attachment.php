<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;

use DotBlue\Mandrill\Utils\MimeTypeDetector;


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
	 * @param string $content
	 * @param string $mimeType
	 */
	public function __construct($name, $content, $mimeType = NULL)
	{
		if ($mimeType === NULL) {
			$mimeType = MimeTypeDetector::fromString($content);
		}
		$this->type = $mimeType;
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
	 * @param string $name
	 * @param string $mimeType
	 * @return static
	 * @throws \RuntimeException
	 */
	public static function fromFile($path, $name = NULL, $mimeType = NULL)
	{
		$fileContent = @file_get_contents($path); // intentionally @
		if ($fileContent === FALSE) {
			throw new \RuntimeException("File '$path' is not readable.'");
		}
		$content = $fileContent;
		$name = $name ?: pathinfo($path, PATHINFO_BASENAME);
		return new static($name, $content, $mimeType);
	}
}
