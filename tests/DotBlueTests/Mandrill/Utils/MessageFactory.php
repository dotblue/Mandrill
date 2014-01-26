<?php
namespace DotBlueTests\Mandrill\Utils;

use DotBlue\Mandrill\Attachment;
use DotBlue\Mandrill\Message;
use DotBlue\Mandrill\TemplateMessage;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MessageFactory
{
	/**
	 * @return Message
	 */
	public static function message()
	{
		$message = new Message();
		MessageFactory::configure($message);
		$message->html = '<html><body>Text of message</body></html>';
		$message->text = 'Text of message';

		return $message;
	}


	/**
	 * @return TemplateMessage
	 */
	public static function templateMessage()
	{
		$message = new TemplateMessage('template');
		MessageFactory::configure($message);
		$message->setEditableRegion('region', 'content');

		return $message;
	}


	public static function configure(\DotBlue\Mandrill\AbstractMessage $message)
	{
		$message->setFrom('sender@example.com', 'Sender of this e-mail');
		$message->setSubject('Subject');

		// Recipients
		$message->addTo('first@example.com', 'First Example', [
			'merge' => 'var',
			'true' => FALSE,
		]);
		$message->addTo('second@example.com', 'Second Example');
		$message->addBcc('third@example.com', 'Third Example', [
			'merge' => 'not a var',
			'yes' => 0,
		]);
		$message->addCc('fourth@example.com')->metadata = [
			'id' => 42,
		];

		// Merge vars
		$message->setGlobalMergeVar('Global', 'var');
		$message->setMergeVar('first@example.com', 'another', 'down');

		// Attachments
		$message->addAttachment(Attachment::fromFile(__DIR__ . '/../files/attachment.txt', 'plain/text'));
		$message->addImage(Attachment::fromFile(__DIR__ . '/../files/php.gif', 'image/gif'));
	}
}
