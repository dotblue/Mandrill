<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
interface IBasicMessage
{
	/**
	 * @return \DotBlue\Mandrill\IEmailAddress
	 */
	function getFrom();


	/**
	 * @return \DotBlue\Mandrill\IRecipient[]
	 */
	function getRecipients();


	/**
	 * @return string
	 */
	function getSubject();


	/**
	 * $name => $value
	 *
	 * @return mixed[]
	 */
	function getGlobalMergeVars();


	/**
	 * $recipient => [
	 *  $name => $value
	 * }
	 *
	 * @return mixed[][]
	 */
	function getMergeVars();


	/**
	 * @return IAttachment[]
	 */
	function getAttachments();


	/**
	 * @return IAttachment[]
	 */
	function getImages();


	/**
	 * @return bool
	 */
	function getAsync();


	/**
	 * @return string
	 */
	function getIpPool();


	/**
	 * @return \DateTime|string
	 */
	function getSendAt();
}
