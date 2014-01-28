<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill\NetteBridge\Mail;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MandrillMailer extends \Nette\Object implements \Nette\Mail\IMailer
{
	/** @var MessageConverter */
	private $converter;

	/** @var \DotBlue\Mandrill\Mailer */
	private $mailer;


	/**
	 * @param MessageConverter $converter
	 * @param \DotBlue\Mandrill\Mailer $mailer
	 */
	public function __construct(MessageConverter $converter, \DotBlue\Mandrill\Mailer $mailer)
	{
		$this->converter = $converter;
		$this->mailer = $mailer;
	}


	/**
	 * @param \Nette\Mail\Message $message
	 * @param callable $setup
	 * @return void
	 */
	public function send(\Nette\Mail\Message $message, $setup = NULL)
	{
		$message = $this->converter->convert($message);
		if ($setup) {
			$setup($message);
		}
		$this->mailer->send($message);
	}
}
