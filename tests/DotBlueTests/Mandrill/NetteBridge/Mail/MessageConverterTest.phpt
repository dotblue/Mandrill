<?php

/**
 * Test: DotBlue\Mandrill\NetteBridge\Mail\MessageConverter.
 *
 * @testCase DotBlueTests\Mandrill\NetteBridge\Mail\MessageConverterTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\NetteBridge\Mail\MessageConverter;
use DotBlue\Mandrill\Exporters\MessageExporter;
use Tester\Assert;


require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MessageConverterTest extends \Tester\TestCase
{
	public function testConversion()
	{
		$converter = new MessageConverter();
		$exporter = new MessageExporter();

		$netteMessage = $converter->convert($this->createNetteMessage());
		$message = $this->createMessage();

		Assert::same($exporter->message($message), $exporter->message($netteMessage));
	}


	private function createNetteMessage()
	{
		$message = new \Nette\Mail\Message();
		$message->setFrom('my@example.com', 'My name');
		$message->addTo('to@example.com', 'To name');
		$message->addTo('to2@example.com');
		$message->addCc('cc@example.com');
		$message->addBcc('bcc@example.com');
		$message->setSubject('Subject');
		$message->setBody('text');
		$message->setHtmlBody('html text');
		$message->addAttachment(__DIR__ . '/../../files/attachment.txt', NULL, 'text/plain');
		$message->addReplyTo('reply@e-mail.com');
		$message->addReplyTo('reply2@e-mail.com');
		$message->setHeader('Date', (new \DateTime('1969-06-20T20:17:40Z'))->format('r'));

		return $message;
	}


	private function createMessage()
	{
		$message = new \DotBlue\Mandrill\Message();
		$message->setFrom('my@example.com', 'My name');
		$message->addTo('to@example.com', 'To name');
		$message->addTo('to2@example.com');
		$message->addCc('cc@example.com');
		$message->addBcc('bcc@example.com');
		$message->setSubject('Subject');
		$message->text = 'text';
		$message->html = 'html text';

		$message->addAttachment(\DotBlue\Mandrill\Attachment::fromFile(__DIR__ . '/../../files/attachment.txt', NULL, 'text/plain'));
		$message->headers += \Nette\Mail\Message::$defaultHeaders;
		$message->headers['Date'] = (new \DateTime('1969-06-20T20:17:40Z'))->format('r');
		$message->headers['Reply-To'] = 'reply@e-mail.com,reply2@e-mail.com';

		return $message;
	}
}

(new MessageConverterTest())->run();
