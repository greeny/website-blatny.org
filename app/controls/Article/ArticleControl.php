<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Controls\Article;

use greeny\Website\Model\Article;
use Nette\Application\UI\Control;

class ArticleControl extends Control
{
	public function render(Article $article)
	{
		$this->template->setFile(__DIR__.'/articleControl.latte');
		$this->template->article = $article;
		$this->template->render();
	}

	public function renderPrevious(Article $article)
	{
		$this->template->setFile(__DIR__.'/articleControlPrevious.latte');
		$this->template->article = $article;
		$this->template->render();
	}

	public function renderNext(Article $article)
	{
		$this->template->setFile(__DIR__.'/articleControlNext.latte');
		$this->template->article = $article;
		$this->template->render();
	}
}
