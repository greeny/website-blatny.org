<?php
/**
 * @author Tomáš Blatný
 */
namespace greeny\Website\Controls;

abstract class BaseMail extends MailControl {
	protected $subject;

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function getSubject()
	{
		return $this->subject;
	}
}
