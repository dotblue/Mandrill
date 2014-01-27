<?php

/**
 * Test: DotBlue\Mandrill\NetteBridge\Mail\MandrillMailer.
 *
 * @testCase DotBlueTests\Mandrill\NetteBridge\Mail\MandrillMailerTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use Tester\Assert;


require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MandrillMailerTest extends \Tester\TestCase
{
	public function testSetupCallback()
	{
		$mandrill = new \DotBlueTests\Mandrill\MandrillMocks\DoNothingMock();
		$exporter = new \DotBlue\Mandrill\Exporters\MessageExporter();
		$mailer = new \DotBlue\Mandrill\Mailer($exporter, $mandrill);

		$converter = new \DotBlue\Mandrill\NetteBridge\Mail\MessageConverter();
		$netteMailer = new \DotBlue\Mandrill\NetteBridge\Mail\MandrillMailer($converter, $mailer);

		$message = new \Nette\Mail\Message();

		Assert::throws(function() use ($netteMailer, $message) {
			$netteMailer->send($message, function(\DotBlue\Mandrill\IMessage $message) {
				throw new \Exception('Called!');
			});
		}, 'Exception', 'Called!');
	}
}

(new MandrillMailerTest())->run();
