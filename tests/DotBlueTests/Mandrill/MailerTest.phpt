<?php

/**
 * Test: DotBlue\Mandrill\Mailer.
 *
 * @testCase DotBlueTests\Mandrill\MailerTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\Mailer;
use DotBlueTests\Mandrill\Utils\MessageFactory;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MailerTest extends \Tester\TestCase
{
	/** @var \DotBlue\Mandrill\Exporters\MessageExporter */
	private $exporter;


	public function testRequestUrl()
	{
		$mailer = $this->createMailer($api = new \DotBlueTests\Mandrill\MandrillMocks\UrlMock());
		$message = MessageFactory::message();
		$templateMessage = MessageFactory::templateMessage();

		$mailer->send($message);
		Assert::same('messages/send', $api->lastUrl);

		$mailer->sendTemplate($templateMessage);
		Assert::same('messages/send-template', $api->lastUrl);
	}


	public function testPassesArguments()
	{
		$mailer = $this->createMailer($api = new \DotBlueTests\Mandrill\MandrillMocks\ParameterMock());
		$message = MessageFactory::message();
		$templateMessage = MessageFactory::templateMessage();

		$mailer->send($message);
		Assert::same($this->getExporter()->message($message), $api->lastParameters);

		$mailer->sendTemplate($templateMessage);
		Assert::same($this->getExporter()->templateMessage($templateMessage), $api->lastParameters);
	}


	public function testThrowsExceptionOnError()
	{
		$mailer = $this->createMailer(new \DotBlueTests\Mandrill\MandrillMocks\FailureMock());
		$message = MessageFactory::message();

		Assert::throws(function() use ($mailer, $message) {
			$mailer->send($message);
		}, 'DotBlue\Mandrill\MailerException');
	}


	/**
	 * @param \DotBlue\Mandrill\IApiCaller $apiCaller
	 * @return Mailer
	 */
	private function createMailer(\DotBlue\Mandrill\IApiCaller $apiCaller)
	{
		return new Mailer($this->getExporter(), $apiCaller);
	}


	/**
	 * @return \DotBlue\Mandrill\Exporters\MessageExporter
	 */
	private function getExporter()
	{
		if (!$this->exporter) {
			$this->exporter = new \DotBlue\Mandrill\Exporters\MessageExporter();
		}
		return $this->exporter;
	}
}

(new MailerTest())->run();
