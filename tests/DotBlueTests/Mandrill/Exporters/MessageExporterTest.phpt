<?php

/**
 * Test: DotBlue\Mandrill\Exporters\MessageExporter.
 *
 * @testCase DotBlueTests\Mandrill\Exporters\MessageExporterTest
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 * @package DotBlue\Mandrill
 */

namespace DotBlueTests\Mandrill\Exporters;

use DotBlue\Mandrill\Exporters\MessageExporter;
use DotBlueTests\Mandrill\Utils\MessageFactory;
use DotBlue\Mandrill\Message;
use DotBlue\Mandrill\TemplateMessage;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MessageExporterTest extends \Tester\TestCase
{
	public function testMessageExport()
	{
		$message = MessageFactory::message();
		$exporter = new MessageExporter();

		$expected = array_merge_recursive($this->getExpected(), [
			'message' => [
				'html' => '<html><body>Text of message</body></html>',
				'text' => 'Text of message',
			]
		]);
		$this->sort($expected);

		$result = $exporter->message($message);
		$this->sort($result);

		Assert::same($expected, $result);
	}


	public function testTemplateMessageExport()
	{
		$message = MessageFactory::templateMessage();
		$exporter = new MessageExporter();

		$expected = array_merge_recursive($this->getExpected(), [
			'template_name' => 'template',
			'template_content' => [
				[
					'name' => 'region',
					'content' => 'content',
				]
			]
		]);
		$this->sort($expected);

		$result = $exporter->templateMessage($message);
		$this->sort($result);

		Assert::same($expected, $result);
	}


	/**
	 * @param array $result
	 */
	private function sort(array &$result)
	{
		foreach ($result as $key => &$value) {
			if (is_array($value)) {
				$this->sort($value);
			}
		}
		ksort($result);
	}


	private function getExpected()
	{
		$attachment = base64_encode(@file_get_contents(__DIR__ . '/../files/attachment.txt'));
		$image = base64_encode(@file_get_contents(__DIR__ . '/../files/php.gif'));

		return [
			'message' => [
				'from_email' => 'sender@example.com',
				'from_name' => 'Sender of this e-mail',
				'subject' => 'Subject',

				// Recipients
				'to' => [
					[
						'email' => 'first@example.com',
						'name' => 'First Example',
						'type' => 'to',
					],
					[
						'email' => 'second@example.com',
						'name' => 'Second Example',
						'type' => 'to',
					],
					[
						'email' => 'third@example.com',
						'name' => 'Third Example',
						'type' => 'bcc',
					],
					[
						'email' => 'fourth@example.com',
						'name' => NULL,
						'type' => 'cc',
					],
				],

				// Recipient metadata
				'recipient_metadata' => [
					[
						'rcpt' => 'fourth@example.com',
						'values' => [
							'id' => 42,
						]
					]
				],

				// Merge vars
				'global_merge_vars' => [
					[
						'name' => 'Global',
						'content' => 'var',
					]
				],
				'merge_vars' => [
					[
						'rcpt' => 'first@example.com',
						'vars' => [
							[
								'name' => 'merge',
								'content' => 'var',
							],
							[
								'name' => 'true',
								'content' => FALSE,
							],
							[
								'name' => 'another',
								'content' => 'down',
							],
						]
					],
					[
						'rcpt' => 'third@example.com',
						'vars' => [
							[
								'name' => 'merge',
								'content' => 'not a var',
							],
							[
								'name' => 'yes',
								'content' => 0,
							],
						]
					],
				],

				// Attachments
				'attachments' => [
					[
						'type' => 'plain/text',
						'name' => 'attachment.txt',
						'content' => $attachment,
					]
				],
				'images' => [
					[
						'type' => 'image/gif',
						'name' => 'php.gif',
						'content' => $image,
					]
				],

				'inline_css' => TRUE,
				'preserve_recipients' => FALSE,
			],
		];
	}
}

(new MessageExporterTest())->run();
