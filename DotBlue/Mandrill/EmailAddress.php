<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class EmailAddress implements IEmailAddress
{
	/** @var string */
	private $email;

	/** @var string */
	private $name;


	/**
	 * @param string $email
	 * @param string $name
	 */
	public function __construct($email, $name = NULL)
	{
		$this->email = $email;
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}


	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
}
