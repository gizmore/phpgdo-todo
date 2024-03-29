<?php
namespace GDO\Todo\Method;

use GDO\Core\GDO;
use GDO\Core\GDT_Checkbox;
use GDO\DB\Query;
use GDO\Table\MethodQueryList;
use GDO\Todo\GDO_Todo;

/**
 * List todo entries.
 *
 * Besides normal List parameters it offers:
 *  - deleted=1 - also shows deleted items
 *  - completed=1 - also shows completed items
 *
 * @author gizmore
 */
final class Search extends MethodQueryList
{

	public function gdoParameters(): array
	{
		return array_merge(parent::gdoParameters(), [
			GDT_Checkbox::make('deleted')->undetermined(),
			GDT_Checkbox::make('completed')->undetermined(),
		]);
	}

	public function gdoTable(): GDO
	{
		return GDO_Todo::table();
	}

	public function getQuery(): Query
	{
		$query = parent::getQuery();
		if (null !== ($deleted = $this->gdoParameterValue('deleted')))
		{
			$condition = $deleted ? 'NOT NULL' : 'NULL';
			$query->where("todo_deleted IS {$condition}");
		}
		if (null !== ($completed = $this->gdoParameterValue('completed')))
		{
			$condition = $completed ? 'NOT NULL' : 'NULL';
			$query->where("todo_completed IS {$condition}");
		}
		return $query;
	}

}
