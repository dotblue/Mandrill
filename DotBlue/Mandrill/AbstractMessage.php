<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
abstract class AbstractMessage implements IBasicMessage
{
	/** @var IEmailAddress */
	private $from;

	/** @var IRecipient[] */
	private $recipients = [];

	/** @var array */
	private $globalMergeVars = [];

	/** @var array */
	private $mergeVars = [];

	/** @var IAttachment[] */
	private $attachments = [];

	/** @var IAttachment[] */
	private $images = [];

	/** @var string */
	public $subject;

	/** @var bool */
	public $async;

	/** @var string */
	public $ipPool;

	/** @var \DateTime|string */
	public $sendAt;


	/**
	 * @return IEmailAddress
	 */
	public function getFrom()
	{
		return $this->from;
	}


	/**
	 * Sets the e-mail sender
	 *
	 * @param string $email
	 * @param string $name
	 * @return $this
	 */
	public function setFrom($email, $name = NULL)
	{
		$this->from = new EmailAddress($email, $name);
		return $this;
	}


	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}


	/**
	 * Sets the e-mail subject
	 *
	 * @param string $subject
	 * @return $this
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}


	/**
	 * @param array $collection
	 * @param object $toBeRemoved
	 * @return bool
	 */
	private function removeObjectFromCollection(array &$collection, $toBeRemoved)
	{
		foreach ($collection as $key => $object) {
			if ($object === $toBeRemoved) {
				unset($collection[$key]);
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * @return bool
	 */
	public function getAsync()
	{
		return $this->async;
	}


	/**
	 * @return string
	 */
	public function getIpPool()
	{
		return $this->ipPool;
	}


	/**
	 * @return \DateTime|string
	 */
	public function getSendAt()
	{
		return $this->sendAt;
	}


	/*******************************************************************************************************************
	 * Merge vars
	 */


	/**
	 * @return array
	 */
	public function getGlobalMergeVars()
	{
		return $this->globalMergeVars;
	}


	/**
	 * Sets the content of a global merge var
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setGlobalMergeVar($name, $value)
	{
		$this->globalMergeVars[$name] = $value;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getMergeVars()
	{
		return $this->mergeVars;
	}


	/**
	 * Sets the content of a merge var for a concrete recipient
	 *
	 * @param string $recipient
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setMergeVar($recipient, $name, $value)
	{
		$this->mergeVars[$recipient][$name] = $value;
		return $this;
	}


	/*******************************************************************************************************************
	 * Recipients
	 */


	/**
	 * @return Recipient[]
	 */
	public function getRecipients()
	{
		return $this->recipients;
	}


	/**
	 * Adds an e-mail recipient
	 *
	 * @param string $email
	 * @param string $name
	 * @param array $mergeVars
	 * @return Recipient
	 */
	public function addTo($email, $name = NULL, $mergeVars = [])
	{
		return $this->addRecipient(new Recipient($email, $name, Recipient::TO), $mergeVars);
	}


	/**
	 * Adds a carbon copy recipient
	 *
	 * @param string $email
	 * @param string $name
	 * @param array $mergeVars
	 * @return Recipient
	 */
	public function addCc($email, $name = NULL, $mergeVars = [])
	{
		return $this->addRecipient(new Recipient($email, $name, Recipient::CC), $mergeVars);
	}


	/**
	 * Adds a blind carbon copy recipient
	 *
	 * @param string $email
	 * @param string $name
	 * @param array $mergeVars
	 * @return Recipient
	 */
	public function addBcc($email, $name = NULL, $mergeVars = [])
	{
		return $this->addRecipient(new Recipient($email, $name, Recipient::BCC), $mergeVars);
	}


	/**
	 * @param IRecipient $recipient
	 * @param array $mergeVars
	 * @return IRecipient
	 */
	public function addRecipient(IRecipient $recipient, $mergeVars = [])
	{
		$this->recipients[] = $recipient;
		foreach ($mergeVars as $name => $value) {
			$this->setMergeVar($recipient->getEmail(), $name, $value);
		}
		return $recipient;
	}


	/**
	 * @param IRecipient $toBeRemoved
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function removeRecipient(IRecipient $toBeRemoved)
	{
		if (!$this->removeObjectFromCollection($this->recipients, $toBeRemoved)) {
			throw new \InvalidArgumentException("Given recipient has not been added to this message.");
		}
		return TRUE;
	}


	/*******************************************************************************************************************
	 * Attachments
	 */


	/**
	 * @return IAttachment[]
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}


	/**
	 * @param IAttachment $attachment
	 * @return Attachment
	 */
	public function addAttachment(IAttachment $attachment)
	{
		return $this->attachments[] = $attachment;
	}


	/**
	 * @param IAttachment $toBeRemoved
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function removeAttachment(IAttachment $toBeRemoved)
	{
		if (!$this->removeObjectFromCollection($this->attachments, $toBeRemoved)) {
			throw new \InvalidArgumentException("Given attachment has not been added to this message.");
		}
		return TRUE;
	}


	/**
	 * @return IAttachment[]
	 */
	public function getImages()
	{
		return $this->images;
	}


	/**
	 * @param IAttachment $image
	 * @return IAttachment
	 */
	public function addImage(IAttachment $image)
	{
		return $this->images[] = $image;
	}


	/**
	 * @param IAttachment $toBeRemoved
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function removeImage(IAttachment $toBeRemoved)
	{
		if (!$this->removeObjectFromCollection($this->images, $toBeRemoved)) {
			throw new \InvalidArgumentException("Given image has not been added to this message.");
		}
		return TRUE;
	}


	/*******************************************************************************************************************
	 * Other options
	 */


	/** @var string[] */
	public $headers = [];

	/** @var bool */
	public $important;

	/** @var bool */
	public $trackOpens;

	/** @var bool */
	public $trackClicks;

	/** @var bool */
	public $autoText;

	/** @var bool */
	public $autoHtml;

	/** @var bool */
	public $inlineCss = TRUE;

	/** @var bool */
	public $urlStripQs;

	/** @var bool */
	public $preserveRecipients = FALSE;

	/** @var bool */
	public $viewContentLink;

	/** @var bool */
	public $bccAddress;

	/** @var string */
	public $trackingDomain;

	/** @var string */
	public $signingDomain;

	/** @var string */
	public $returnPathDomain;

	/** @var bool */
	public $merge;

	/** @var string[] */
	public $tags;

	/** @var string */
	public $subaccount;

	/** @var string[] */
	public $googleAnalyticsDomains = [];

	/** @var string[]|string */
	public $googleAnalyticsCampaign;

	/** @var string[] */
	public $metadata = [];


	/**
	 * @return []
	 */
	public function getHeaders()
	{
		return $this->headers;
	}


	/**
	 * @param string $header
	 * @param string $value
	 */
	public function addHeader($header, $value)
	{
		$this->headers[$header] = $value;
	}

}
