<?php
namespace GDO\Todo\Method;

use GDO\Date\Time;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_Validator;
use GDO\Form\MethodForm;
use GDO\Todo\GDO_Todo;
use GDO\Todo\GDT_Todo;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * Mark a todo item as completed.
 *
 * @author gizmore
 */
final class Completed extends MethodForm
{

	public function createForm(GDT_Form $form): void
	{
		$form->addFields(
			GDT_Todo::make('id'),
			GDT_User::make('by')->label('by')->fallbackCurrentUser(),
		);
		$form->addFields(
			GDT_Validator::make('already_completed')->validatorFor($form, 'id', [$this, 'validateAlreadyCompleted']),
			GDT_AntiCSRF::make(),
		);
		$form->actions()->addField(GDT_Submit::make());
	}

	public function formValidated(GDT_Form $form)
	{
		$todo = $this->getTodoEntry();
		$user = $this->getTodoCompletor();
		$todo->saveVars([
			'todo_completed' => Time::getDate(),
			'todo_completor' => $user->getID(),
		]);
		return $this->message('msg_todo_completed', [
			$todo->getID(),
			$user->renderUserName(),
			$todo->displayCompleted(),
		]);
	}

	/**
	 * @return GDO_Todo
	 */
	public function getTodoEntry()
	{
		return $this->getForm()->getFormValue('id');
	}

	/**
	 * @return GDO_User
	 */
	public function getTodoCompletor()
	{
		if ($user = $this->getForm()->getFormValue('by'))
		{
			return $user;
		}
		return GDO_User::current();
	}

	public function validateAlreadyCompleted(GDT_Form $form, GDT_Todo $field, GDO_Todo $value = null)
	{
		if ($value && $value->isCompleted())
		{
			return $field->error('err_todo_already_completed');
		}
		return true;
	}

}
