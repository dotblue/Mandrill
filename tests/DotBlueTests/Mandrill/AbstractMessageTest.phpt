<?php

/**
 * Test: DotBlue\Mandrill\AbstractMessage.
 *
 * @testCase DotBlueTests\Mandrill\AbstractMessageTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill;

use DotBlue\Mandrill\AbstractMessage;
use DotBlue\Mandrill\IRecipient;
use DotBlue\Mandrill\Attachment;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class AbstractMessageTest extends \Tester\TestCase
{
	public function testFrom()
	{
		$message = new BasicMessage();
		$message->setFrom('tyrion@casterlyrock.sif', 'Tyrion Lannister');

		$from = $message->getFrom();
		Assert::true($from instanceof \DotBlue\Mandrill\IEmailAddress);
	}


	public function testSubject()
	{
		$message = new BasicMessage();
		$message->setSubject('Winter is coming!');

		Assert::same('Winter is coming!', $message->getSubject());
	}


	public function testRecipients()
	{
		$message = new BasicMessage();
		$message->setSubject('For king eyes only.');
		Assert::true($message->addTo('robert@baratheon.sif', 'Robert Baratheon') instanceof IRecipient);
		$message->addCc('jon@arryn.sif', 'Jon Arryn');
		$message->addBcc('varys@thespider.sif', 'Varys');

		$recipients = $message->getRecipients();
		Assert::same(IRecipient::TO, $recipients[0]->getType());
		Assert::same(IRecipient::CC, $recipients[1]->getType());
		Assert::same(IRecipient::BCC, $recipients[2]->getType());

		$message->removeRecipient($recipients[1]);
		$newRecipients = $message->getRecipients();
		Assert::false(in_array($recipients[1], $newRecipients, TRUE));
		Assert::true(in_array($recipients[0], $newRecipients, TRUE));
		Assert::true(in_array($recipients[2], $newRecipients, TRUE));
	}


	public function testGlobalMergeVars()
	{
		$message = new BasicMessage();
		$message->setGlobalMergeVar('name', 'value');
		$message->setGlobalMergeVar('name', 'you');
		$message->setGlobalMergeVar('answer', 42);

		Assert::same([
			'name' => 'you',
			'answer' => 42,
		], $message->getGlobalMergeVars());
	}


	public function testMergeVars()
	{
		$message = new BasicMessage();

		$message->setMergeVar('email@example.com', 'Merge', 'var');
		$message->setMergeVar('martin@grrm.sif', 'You', 'Rock!');
		$message->addTo('martin@grrm.sif', NULL, [
			'cool' => TRUE,
		]);

		Assert::same([
			'email@example.com' => [
				'Merge' => 'var',
			],
			'martin@grrm.sif' => [
				'You' => 'Rock!',
				'cool' => TRUE,
			]
		], $message->getMergeVars());
	}


	public function testAttachments()
	{
		$message = new BasicMessage();

		$message->addAttachment($attachment1 = new Attachment('name', 'type', 'content'));
		$message->addAttachment($attachment2 = new Attachment('name2', 'type2', 'content2'));
		$message->addImage($image1 = new Attachment('name2', 'type2', 'content2'));
		$message->addImage($image2 = new Attachment('name2', 'type2', 'content2'));

		Assert::same([$attachment1, $attachment2], $message->getAttachments());
		Assert::same([$image1, $image2], $message->getImages());

		$message->removeAttachment($attachment1);
		$attachments = $message->getAttachments();
		Assert::false(in_array($attachment1, $attachments, TRUE));
		Assert::true(in_array($attachment2, $attachments, TRUE));

		$message->removeImage($image1);
		$images = $message->getImages();
		Assert::false(in_array($image1, $images, TRUE));
		Assert::true(in_array($image2, $images, TRUE));
	}
}

class BasicMessage extends AbstractMessage
{

}

(new AbstractMessageTest())->run();
