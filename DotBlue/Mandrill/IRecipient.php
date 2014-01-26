<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IRecipient
{
	/** @var string */
	const TO = 'to';
	const CC = 'cc';
	const BCC = 'bcc';


	/**
	 * @return string
	 */
	function getType();


	/**
	 * @return array
	 */
	function getMetadata();
}
