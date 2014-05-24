<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\PublicModule;

use greeny\Website\Controls\Form;
use greeny\Website\Model\UserRepository;
use Nette\Security\AuthenticationException;

class UserPresenter extends BasePublicPresenter
{
	/** @var UserRepository @inject */
	public $userRepository;

	public function actionRegister()
	{
		if($this->user->isLoggedIn()) {
			$this->redirect(':Public:User:detail', array('id' => strtolower($this->user->getIdentity()->nick)));
		}
	}

	public function actionLogin()
	{
		if($this->user->isLoggedIn()) {
			$this->redirect(':Public:User:detail', array('id' => strtolower($this->user->getIdentity()->nick)));
		}
	}

	protected function createComponentRegisterForm()
	{
		$form = $this->createForm();
		$form->addText('nick', 'Nick')
			->setRequired('Please enter your nick.')
			->addRule($form::PATTERN, 'Your nick can have at most 255 characters and can contain only letters, numbers, dash and underscore.', '[a-zA-Z0-9\-_]{1,255}');
		$form->addPassword('password', 'Password')
			->setRequired('Please enter your password.');
		$form->addPassword('passwordCheck', 'Retype password')
			->addRule($form::EQUAL, 'Passwords don\'t match.', $form['password']);
		$form->addText('email', 'Email')
			->addRule($form::EMAIL, 'Your email address must be valid.');

		$form->addSubmit('register', 'Register');
		$form->onSuccess[] = $this->registerFormSuccess;
		return $form;
	}

	public function registerFormSuccess(Form $form)
	{
		$v = $form->getValues();
		unset($v->passwordCheck);
		try {
			$this->userRepository->register($v);
			$this->flashSuccess("User '$v->nick' registered successfully.");
			$this->redirect('login');
		} catch(AuthenticationException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
	}

	protected function createComponentLoginForm()
	{
		$form = $this->createForm();
		$form->addText('nick', 'Nick')
			->setRequired('Please enter your nick.');
		$form->addPassword('password', 'Password')
			->setRequired('Please enter your password.');
		$form->addCheckbox('remember', 'Remember me on this computer.');
		$form->addHidden('back', $this->getParameter('back'));
		$form->addSubmit('login', 'Login');
		$form->onSuccess[] = $this->loginFormSuccess;
		return $form;
	}

	public function loginFormSuccess(Form $form)
	{
		$v = $form->getValues();
		try {
			$this->user->login($v->nick, $v->password);
			if($v->remember) {
				$this->user->setExpiration('+14 days', FALSE, TRUE);
			} else {
				$this->user->setExpiration('+30 minutes', TRUE, TRUE);
			}
			$this->flashSuccess("Login successful.");
			if($v->back) {
				$this->restoreRequest($v->back);
			} else {
				$this->redirect(':Public:dashboard:default');
			}
		} catch(AuthenticationException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
	}
}
