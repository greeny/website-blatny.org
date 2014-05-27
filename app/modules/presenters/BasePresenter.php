<?php

namespace greeny\Website;

use greeny\Website\Controls\ArticleControl;
use greeny\Website\Controls\ProjectControl;
use greeny\Website\Controls\UserControl;
use greeny\Website\Model\ArticleRepository;
use greeny\Website\Model\ProjectRepository;
use greeny\Website\Templating\Helpers;
use greeny\Website\Controls\Form;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
	/** @var ArticleRepository @inject */
	public $articleRepository;

	/** @var ProjectRepository @inject */
	public $projectRepository;

	public function createForm()
	{
		$form = new Form();
		return $form;
	}

	public function beforeRender()
	{
		parent::beforeRender();
		Helpers::prepareTemplate($this->template);
		$this->template->panelProjects = $this->projectRepository->getPanelProjects();
		$this->template->panelArticles = $this->articleRepository->getPanelArticles();
	}

	public function getParamByName($name)
	{
		return $this->params[$name];
	}

	public function handleLogin()
	{
		if(!$this->user->isLoggedIn()) {
			$this->redirect(':Public:User:login', array('back' => $this->storeRequest()));
		}
	}

	public function handleLogout()
	{
		if($this->user->isLoggedIn()) {
			$this->user->logout(TRUE);
			$this->flashSuccess('Byl jsi odhlášen.');
		}
		$this->refresh();
	}

	public function flashError($message)
	{
		return $this->flashMessage($message, 'danger');
	}

	public function flashSuccess($message)
	{
		return $this->flashMessage($message, 'success');
	}

	public function refresh()
	{
		$this->redirect('this');
	}

	public function createComponentUser()
	{
		return new UserControl();
	}

	public function createComponentArticle()
	{
		return new ArticleControl();
	}

	public function createComponentProject()
	{
		return new ProjectControl();
	}
}
