<?php

/**
 * Test: DotBlue\Mandrill\EmailAddress.
 *
 * @testCase DotBlueTests\Mandrill\EmailAddressTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\EmailAddress;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class EmailAddressTest extends \Tester\TestCase
{
	public function testConstructor()
	{
		$email = new EmailAddress('email@example.com', 'Recipient Name');

		Assert::same('email@example.com', $email->getEmail());
		Assert::same('Recipient Name', $email->getName());
	}
}

(new EmailAddressTest())->run();
