<?php
namespace GDO\Todo\Method;

use GDO\Core\Method;
use GDO\Todo\GDO_Todo;
use GDO\Todo\GDT_Todo;
use GDO\UI\GDT_HTML;

/**
 * Show a todo item.
 *
 * @see GDO_Todo
 * @author gizmore
 */
final class Show extends Method
{

	public function gdoParameters(): array
	{
		return [
			GDT_Todo::make('id'),
		];
	}

	public function execute()
	{
		return GDT_HTML::make()->var($this->getToDo()->renderCard());
	}

	/**
	 * @return GDO_Todo
	 */
	public function getToDo()
	{
		return $this->gdoParameterValue('id');
	}

}
