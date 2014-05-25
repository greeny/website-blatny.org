<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Controls;

use greeny\Website\Model\Project;
use Nette\Application\UI\Control;

class ProjectControl extends Control
{
	public function render(Project $project)
	{
		$this->template->setFile(__DIR__.'/projectControl.latte');
		$this->template->project = $project;
		$this->template->render();
	}
}
