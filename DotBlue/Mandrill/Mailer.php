<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class Mailer
{
	/** @var \DotBlue\Mandrill\Exporters\IMessageExporter */
	private $exporter;

	/** @var IApiCaller */
	private $api;


	/**
	 * @param \DotBlue\Mandrill\Exporters\IMessageExporter $exporter
	 * @param IApiCaller $api
	 */
	public function __construct(\DotBlue\Mandrill\Exporters\IMessageExporter $exporter, IApiCaller $api)
	{
		$this->exporter = $exporter;
		$this->api = $api;
	}


	/**
	 * @param IMessage $message
	 * @return string
	 */
	public function send(IMessage $message)
	{
		$parameters = $this->exporter->message($message);
		return $this->sendRequest('messages/send', $parameters);
	}


	/**
	 * @param ITemplateMessage $message
	 * @return string
	 */
	public function sendTemplate(ITemplateMessage $message)
	{
		$parameters = $this->exporter->templateMessage($message);
		return $this->sendRequest('messages/send-template', $parameters);
	}


	/**
	 * @param string $url
	 * @param array $parameters
	 * @return string
	 */
	private function sendRequest($url, array $parameters)
	{
		$response = $this->api->call($url, $parameters);
		$this->checkMessageResponse($response);
		return $response;
	}


	/**
	 * @param array $response
	 */
	private function checkMessageResponse(array $response)
	{
		$failures = [];
		foreach ($response as $emailResponse) {
			if (in_array($emailResponse['status'], ['rejected', 'invalid'])) {
				$failures[] = $emailResponse;
			}
		}
		if ($failures) {
			$this->castError($failures);
		}
	}


	/**
	 * @param array $failures
	 * @throws MailerException
	 */
	private function castError(array $failures)
	{
		$rejectReasons = [];
		foreach ($failures as $email) {
			$reason = $email['reject_reason'] ?: 'unknown';
			$rejectReasons[$reason] = $reason;
		}
		$exception = new MailerException('Some of the e-mails were rejected due to: ' . implode(', ', $rejectReasons));
		$exception->failures = $failures;
		throw $exception;
	}
}
