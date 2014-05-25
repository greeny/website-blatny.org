<?php

namespace greeny\Website;

use greeny\Website\Controls\ArticleControl;
use greeny\Website\Controls\ProjectControl;
use greeny\Website\Controls\UserControl;
use greeny\Website\Templating\Helpers;
use greeny\Website\Controls\Form;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
	public function createForm()
	{
		$form = new Form();
		return $form;
	}

	public function beforeRender()
	{
		parent::beforeRender();
		Helpers::prepareTemplate($this->template);
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
