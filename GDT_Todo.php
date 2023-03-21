<?php
namespace GDO\Todo;

use GDO\Core\GDT_Object;

/**
 * A todo entry selector.
 *
 * @author gizmore
 */
final class GDT_Todo extends GDT_Object
{

	protected function __construct()
	{
		parent::__construct();
		$this->table(GDO_Todo::table());
		$this->notNull();
	}

}
