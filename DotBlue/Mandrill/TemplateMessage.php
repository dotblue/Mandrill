<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\Mandrill;


/**
 * @author Pavel Kučera
 * @author dotBlue (http://dotblue.net)
 */
class TemplateMessage extends AbstractMessage implements ITemplateMessage
{
	/** @var string */
	private $templateName;

	/** @var array */
	private $editableRegions = [];


	/**
	 * @param string $templateName
	 */
	public function __construct($templateName = NULL)
	{
		$this->setTemplateName($templateName);
	}


	/**
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->templateName;
	}


	/**
	 * @param string $templateName
	 * @return $this
	 */
	public function setTemplateName($templateName)
	{
		$this->templateName = $templateName;
	}


	/**
	 * @return array
	 */
	public function getEditableRegions()
	{
		return $this->editableRegions;
	}


	/**
	 * @param string $name
	 * @param string $content
	 */
	public function setEditableRegion($name, $content)
	{
		$this->editableRegions[$name] = $content;
	}
}
