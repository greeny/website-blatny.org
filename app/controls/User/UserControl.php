<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Controls;

use greeny\Website\Model\User;
use Nette\Application\UI\Control;

class UserControl extends Control
{
	public function render(User $user)
	{
		$this->template->setFile(__DIR__.'/userControl.latte');
		$this->template->user = $user;
		$this->template->render();
	}
}
