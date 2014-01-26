<?php

/**
 * Test: DotBlue\Mandrill\Message.
 *
 * @testCase DotBlueTests\Mandrill\MessageTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\Message;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MessageTest extends \Tester\TestCase
{
	public function testContent()
	{
		$message = new Message();
		$message->html = '<b>HTML!</b>';
		$message->text = 'HTML!';

		Assert::same('<b>HTML!</b>', $message->getHtml());
		Assert::same('HTML!', $message->getText());
	}
}

(new MessageTest())->run();
