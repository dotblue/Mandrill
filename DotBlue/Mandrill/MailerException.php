<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MailerException extends \Exception
{
	/** @var array */
	public $failures = [];
}
