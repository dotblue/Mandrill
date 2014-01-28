<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill\Exporters;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class MessageExporter implements IMessageExporter
{
	/** @var string[] */
	private $protectedMessageProperties = [
		'async',
		'ipPool',
		'sendAt',
	];


	/**
	 * @param \DotBlue\Mandrill\IMessage $message
	 * @return array
	 */
	public function message(\DotBlue\Mandrill\IMessage $message)
	{
		return array_replace_recursive($this->exportMessage($message), [
			'message' => [
				'html' => $message->getHtml(),
				'text' => $message->getText(),
			],
		]);
	}


	/**
	 * @param \DotBlue\Mandrill\ITemplateMessage $message
	 * @return array
	 */
	public function templateMessage(\DotBlue\Mandrill\ITemplateMessage $message)
	{
		return array_replace_recursive($this->exportMessage($message), [
			'template_name' => $message->getTemplateName(),
			'template_content' => $this->exportVariables($message->getEditableRegions()),
		]);
	}


	/**
	 * @param \DotBlue\Mandrill\IBasicMessage $message
	 * @return array
	 */
	private function exportMessage(\DotBlue\Mandrill\IBasicMessage $message)
	{
		return array_replace_recursive($this->exportOptions($message), [
			'message' => [
				'from_email' => $message->getFrom() ? $message->getFrom()->getEmail() : NULL,
				'from_name' => $message->getFrom() ? $message->getFrom()->getName() : NULL,
				'subject' => $message->getSubject(),

				// Recipient
				'to' => $this->exportRecipients($message->getRecipients()),
				'recipient_metadata' => $this->exportRecipientMetadata($message->getRecipients()),

				// Merge vars
				'global_merge_vars' => $this->exportVariables($message->getGlobalMergeVars()),
				'merge_vars' => $this->exportMergeVariables($message->getMergeVars()),

				// Attachments
				'attachments' => $this->exportAttachments($message->getAttachments()),
				'images' => $this->exportAttachments($message->getImages()),
			],
		]);
	}


	/**
	 * @param array $variables
	 * @return array
	 */
	private function exportVariables(array $variables)
	{
		$list = [];
		foreach ($variables as $name => $value) {
			$list[] = [
				'name' => $name,
				'content' => $value,
			];
		}
		return $list;
	}


	/**
	 * @param \DotBlue\Mandrill\Recipient[] $recipients
	 * @return array
	 */
	private function exportRecipients(array $recipients)
	{
		$list = [];
		foreach ($recipients as $recipient) {
			$list[] = [
				'email' => $recipient->getEmail(),
				'name' => $recipient->getName(),
				'type' => $recipient->getType(),
			];
		}
		return $list;
	}


	/**
	 * @param array $mergeVariables
	 * @return array
	 */
	private function exportMergeVariables(array $mergeVariables)
	{
		$list = [];
		foreach ($mergeVariables as $recipient => $vars) {
			$list[] = [
				'rcpt' => $recipient,
				'vars' => $this->exportVariables($vars),
			];
		}
		return $list;
	}


	/**
	 * @param \DotBlue\Mandrill\Recipient[] $recipients
	 * @return array
	 */
	private function exportRecipientMetadata(array $recipients)
	{
		$metadata = [];
		foreach ($recipients as $recipient) {
			if (!$recipient->metadata) {
				continue;
			}

			$metadata[] = [
				'rcpt' => $recipient->getEmail(),
				'values' => $recipient->metadata,
			];
		}
		return $metadata;
	}


	/**
	 * @param \DotBlue\Mandrill\Attachment[] $attachments
	 * @return array
	 */
	private function exportAttachments(array $attachments)
	{
		$list = [];
		foreach ($attachments as $attachment) {
			$list[] = [
				'type' => $attachment->getType(),
				'name' => $attachment->getName(),
				'content' => base64_encode($attachment->getContent()),
			];
		}
		return $list;
	}


	/*******************************************************************************************************************
	 * Public properties -> options
	 */


	/**
	 * @param \DotBlue\Mandrill\IBasicMessage $message
	 * @return array
	 */
	private function exportOptions(\DotBlue\Mandrill\IBasicMessage $message)
	{
		$options = get_object_vars($message);
		$export = [
			'message' => [],
			'async' => $message->getAsync(),
			'ip_pool' => $message->getIpPool(),
			'send_at' => $this->exportSendAt($message),
		];
		foreach ($options as $name => $value) {
			if ($value === NULL || is_array($value) && count($value) === 0 || in_array($name, $this->protectedMessageProperties)) {
				continue;
			}

			$export['message'][$this->processOptionName($name)] = $value;
		}
		return $export;
	}


	/**
	 * @param \DotBlue\Mandrill\IBasicMessage $message
	 * @return \DateTime|string|NULL
	 */
	private function exportSendAt(\DotBlue\Mandrill\IBasicMessage $message)
	{
		$sendAt = $message->getSendAt();
		if (!$sendAt) {
			return NULL;
		}

		if ($sendAt instanceof \DateTime) {
			return $sendAt->format('Y-m-d H:i:s');
		}

		return $sendAt;
	}


	/**
	 * @param string $name
	 * @return mixed
	 */
	private function processOptionName($name)
	{
		return preg_replace_callback('~([A-Z])~', [$this, 'toUnderscored'], $name);
	}


	/**
	 * @param array $m
	 * @return string
	 */
	private function toUnderscored(array $m)
	{
		return '_' . strtolower($m[1]);
	}
}
