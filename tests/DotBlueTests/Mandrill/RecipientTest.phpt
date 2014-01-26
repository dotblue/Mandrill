<?php

/**
 * Test: DotBlue\Mandrill\Recipient.
 *
 * @testCase DotBlueTests\Mandrill\RecipientTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\Recipient;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class RecipientTest extends \Tester\TestCase
{
	public function testConstructor()
	{
		$recipient = new Recipient('email@example.com', 'Recipient Name', Recipient::CC);
		Assert::same(Recipient::CC, $recipient->getType());
	}


	public function testMetadata()
	{
		$recipient = new Recipient('fake@example.com');
		$recipient->metadata = [
			'some' => 'metadata',
		];

		Assert::same([
			'some' => 'metadata',
		], $recipient->getMetadata());
	}
}

(new RecipientTest())->run();
