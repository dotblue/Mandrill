<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class Recipient extends EmailAddress implements IRecipient
{
	/** @var string */
	private $type;

	/** @var string[] */
	public $metadata = [];


	/**
	 * @param string $email
	 * @param string $name
	 * @param string $type
	 */
	public function __construct($email, $name = NULL, $type = NULL)
	{
		parent::__construct($email, $name);
		$this->type = $type;
	}


	/**
	 * @return string|NULL
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @return array|\string[]
	 */
	public function getMetadata()
	{
		return $this->metadata;
	}
}
