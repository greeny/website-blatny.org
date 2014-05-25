<?php
/**
 * @author Tomáš Blatný
 */
namespace greeny\Website\Controls;

use Nette\Application\UI\Form as NForm;
use Nette\Forms\Controls;

class Form extends NForm {
	public function addTextArea($name, $label = NULL, $cols = NULL, $rows = NULL)
	{
		return parent::addTextArea($name, $label, $cols, $rows)
			->setAttribute('rows', 5);
	}

	public function render()
	{
		$renderer = $this->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=form-control-group';
		$renderer->wrappers['label']['container'] = 'div class="form-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

		// make form and controls compatible with Twitter Bootstrap
		$this->getElementPrototype()->class('form-horizontal');

		foreach ($this->getControls() as $control) {
			if ($control instanceof Controls\Button) {
				$control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
				$usedPrimary = TRUE;

			} elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
				$control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
			}
		}
		parent::render();
	}
}
