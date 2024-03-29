<?php
namespace GDO\Todo\Method;

use GDO\Core\GDO;
use GDO\Core\GDO_Exception;
use GDO\Core\GDT;
use GDO\Form\GDT_Form;
use GDO\Form\MethodCrud;
use GDO\Todo\GDO_Todo;

/**
 * This CRUD method for GDO_Todo does not allow create.
 * Create is done in the Add method.
 * Edit and delete is for staff only.
 *
 * @author gizmore
 * @see Add
 */
final class Edit extends MethodCrud
{

	public function gdoTable(): GDO
	{
		return GDO_Todo::table();
	}

	public function featureCreate(): bool
	{
		return false;
	}

	public function hrefList(): string
	{
		return href('Todo', 'Search');
	}

	public function onMethodInit(): ?GDT
	{
		if (!$this->getCRUDID())
		{
			return $this->error('err_cannot_create_todo');
		}
		return parent::onMethodInit();
	}


	/**
	 * @throws GDO_Exception
	 */
	protected function createForm(GDT_Form $form): void
	{
		parent::createForm($form);
		$form->getField('todo_text')->notNull(false);
	}

}
