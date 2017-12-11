<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill\NetteBridge\Mail;

use Nette\Utils\Strings;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MessageConverter
{

	use \Nette\SmartObject;

	/**
	 * @param \Nette\Mail\Message $originalMessage
	 * @return \DotBlue\Mandrill\Message
	 */
	public function convert(\Nette\Mail\Message $originalMessage)
	{
		$originalMessage = clone $originalMessage;

		$message = new \DotBlue\Mandrill\Message();
		$this->setRecipients($message, $originalMessage);
		$this->setAttachments($message, $originalMessage);

		// From
		list ($from, $name) = $this->processEmail($originalMessage->getFrom());
		$message->setFrom($from, $name);
		$originalMessage->clearHeader('From');

		// Subject
		$message->subject = $originalMessage->getSubject();
		$originalMessage->clearHeader('Subject');

		// Content
		$message->text = $originalMessage->getBody();
		$message->html = $originalMessage->getHtmlBody();

		foreach ($originalMessage->getHeaders() as $name => $content) {
			$message->headers[$name] = $originalMessage->getEncodedHeader($name);
		}

		return $message;
	}


	/**
	 * @param \DotBlue\Mandrill\Message $message
	 * @param \Nette\Mail\Message $originalMessage
	 */
	private function setRecipients(\DotBlue\Mandrill\Message $message, \Nette\Mail\Message $originalMessage)
	{
		$to = $originalMessage->getHeader('To') ?: [];
		foreach ($to as $email => $name) {
			$message->addTo($email, $name);
		}

		$cc = $originalMessage->getHeader('Cc') ?: [];
		foreach ($cc as $email => $name) {
			$message->addCc($email, $name);
		}

		$bcc = $originalMessage->getHeader('Bcc') ?: [];
		foreach ($bcc as $email => $name) {
			$message->addBcc($email, $name);
		}

		$originalMessage->clearHeader('To');
		$originalMessage->clearHeader('Cc');
		$originalMessage->clearHeader('Bcc');
	}


	/**
	 * @param \DotBlue\Mandrill\Message $message
	 * @param \Nette\Mail\Message $originalMessage
	 */
	private function setAttachments(\DotBlue\Mandrill\Message $message, \Nette\Mail\Message $originalMessage)
	{
		// Dirty trick
		$stealAttachments = \Closure::bind(function() {
			return $this->attachments;
		}, $originalMessage, $originalMessage);

		foreach ($stealAttachments() as $attachment) {
			$name = $this->parseAttachmentName($attachment);
			$type = $attachment->getHeader('Content-Type');
			$content = $attachment->getBody();

			$message->addAttachment(new \DotBlue\Mandrill\Attachment($name, $content, $type));
		}
	}


	/**
	 * @param \Nette\Mail\MimePart $attachment
	 * @return string
	 */
	private function parseAttachmentName(\Nette\Mail\MimePart $attachment)
	{
		$contentDisposition = $attachment->getHeader('Content-Disposition');
		$m = Strings::match($contentDisposition, '~filename="(.*)"$~');
		return $m[1];
	}


	/**
	 * @param array $email
	 * @return array
	 */
	private function processEmail(array $email = NULL)
	{
		$email = $email ?: ['' => ''];
		return [array_keys($email)[0], array_values($email)[0]];
	}
}
