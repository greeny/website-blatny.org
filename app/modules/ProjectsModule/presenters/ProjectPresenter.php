<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\ProjectsModule;

use greeny\Website\Controls\Form;
use greeny\Website\Model\Project;
use greeny\Website\Model\ProjectRepository;
use Nette\Utils\Strings;

class ProjectPresenter extends BaseProjectsPresenter
{
	/** @var Project */
	protected $project;

	public function actionCompose()
	{
		if(!$this->user->isLoggedIn() || !$this->user->isAllowed('project', 'compose')) {
			$this->redirect('detail');
		}
	}

	public function actionDetail($slug = NULL)
	{
		if($slug !== NULL) {
			if(!$this->project = $this->projectRepository->findBySlug($slug)) {
				$this->flashError("This project does not exist.");
				$this->redirect('detail');
			}
		} else {
			$this->setView('list');
		}
	}

	public function actionEdit($slug)
	{
		if(!$this->user->isLoggedIn() || !$this->user->isAllowed('project', 'edit')) {
			$this->redirect('detail', array('slug' => $slug));
		}
		if(!$this->project = $this->projectRepository->findBySlug($slug)) {
			$this->flashError("This project does not exist.");
			$this->redirect('detail');
		}
	}

	public function renderEdit()
	{
		$this->template->project = $this->project;
	}

	public function renderList()
	{
		$this->template->projects = $this->projectRepository->findAll();
	}

	public function renderDetail()
	{
		$this->template->project = $this->project;
	}

	protected function createComponentComposeProjectForm()
	{
		$form = $this->createForm();
		$form->addText('name', 'Name')
			->setRequired('Please enter name.');
		$form->addTextArea('description', 'Description')
			->setRequired('Please enter description');
		$form->addText('github', 'Github link');
		$form->addText('composer', 'Composer link');
		$form->addText('documentation', 'Documentation link');
		$form->addText('live', 'Live demo link');
		$form->addSubmit('compose', 'Compose');
		$form->onSuccess[] = $this->composeProjectFormSuccess;
		return $form;
	}

	public function composeProjectFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$project = Project::from($v);
		$project->slug = $slug = Strings::webalize($project->name);
		$i = 1;
		while($this->projectRepository->findBySlug($project->slug)) {
			$project->slug = $slug . '-' . $i++;
		}
		$this->projectRepository->persist($project);
		$this->flashSuccess("Project '$project->name' added successfully.");
		$this->redirect('detail', array('slug' => $project->slug));
	}

	protected function createComponentEditProjectForm()
	{
		$form = $this->createForm();
		$form->addTextArea('description', 'Description')
			->setRequired('Please enter description');
		$form->addText('github', 'Github link');
		$form->addText('composer', 'Composer link');
		$form->addText('documentation', 'Documentation link');
		$form->addText('live', 'Live demo link');
		$form->addSubmit('edit', 'Edit');
		$form->setDefaults($this->project->getData());
		$form->onSuccess[] = $this->editProjectFormSuccess;
		return $form;
	}

	public function editProjectFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->project->update($v);
		$this->projectRepository->persist($this->project);
		$this->flashSuccess("Project '{$this->project->name}' was updated.");
		$this->refresh();
	}
}
