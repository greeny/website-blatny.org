<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\BlogModule;

use greeny\Website\Controls\Form;
use greeny\Website\Model\Article;
use greeny\Website\Model\ArticleRepository;
use greeny\Website\Model\UserRepository;
use Nette\Utils\Strings;

class ArticlePresenter extends BaseBlogPresenter
{
	/** @var UserRepository @inject */
	public $userRepository;

	/** @var Article */
	protected $article;

	public function actionCompose()
	{
		if(!$this->user->isLoggedIn() || !$this->user->isAllowed('article', 'compose')) {
			$this->redirect('detail');
		}
	}

	public function actionDetail($slug = NULL)
	{
		if($slug !== NULL) {
			if(!$this->article = $this->articleRepository->findBySlug($slug)) {
				$this->flashError("This article does not exist.");
				$this->redirect('detail');
			}
		} else {
			$this->setView('list');
		}
	}

	public function actionEdit($slug)
	{
		if(!$this->user->isLoggedIn() || !$this->user->isAllowed('article', 'edit')) {
			$this->redirect('detail', array('slug' => $slug));
		}
		if(!$this->article = $this->articleRepository->findBySlug($slug)) {
			$this->flashError("This article does not exist.");
			$this->redirect('detail');
		}
	}

	public function renderEdit()
	{
		$this->template->article = $this->article;
	}

	public function renderList()
	{
		$this->template->notPublished = $this->articleRepository->findNotPublished();
		$this->template->articles = $this->articleRepository->findAll();
	}

	public function renderDetail()
	{
		$this->template->article = $this->article;
		$this->template->previous = $this->articleRepository->getPrevious($this->article);
		$this->template->next = $this->articleRepository->getNext($this->article);
	}

	protected function createComponentComposeArticleForm()
	{
		$form = $this->createForm();
		$form->addText('title', 'Title')
			->setRequired('Please enter title.');
		$form->addTextArea('content', 'Content')
			->setRequired('Please enter content');
		$form->addCheckbox('published', 'Publish now');
		$form->addSubmit('compose', 'Compose');
		$form->onSuccess[] = $this->composeArticleFormSuccess;
		return $form;
	}

	public function composeArticleFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$article = Article::from($v);
		$article->slug = $slug = Strings::webalize($article->title);
		$i = 1;
		while($this->articleRepository->findBySlug($article->slug)) {
			$article->slug = $slug . '-' . $i++;
		}
		$article->user = $this->userRepository->findByNick($this->user->getIdentity()->nick);
		$article->time = Time();
		$this->articleRepository->persist($article);
		$this->flashSuccess("Article '$article->title' added successfully.");
		$this->redirect(':Blog:Article:detail', array('slug' => $article->slug));
	}

	protected function createComponentEditArticleForm()
	{
		$form = $this->createForm();
		$form->addTextArea('content', 'Content')
			->setRequired('Please enter content');
		$form->addCheckbox('published', 'Publish now');
		$form->addSubmit('edit', 'Edit');
		$form->setDefaults($this->article->getData());
		$form->onSuccess[] = $this->editArticleFormSuccess;
		return $form;
	}

	public function editArticleFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->article->update($v);
		$this->articleRepository->persist($this->article);
		$this->flashSuccess("Article '{$this->article->title}' was updated.");
		$this->refresh();
	}
}
