<?php
namespace GDO\Todo\Method;

use GDO\Table\MethodQueryList;
use GDO\Todo\GDO_Todo;
use GDO\Core\GDO;
use GDO\Core\GDT_Checkbox;
use GDO\DB\Query;

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
    public function gdoParameters() : array
    {
        return array_merge(parent::gdoParameters(), [
            GDT_Checkbox::make('deleted')->undetermined(),
            GDT_Checkbox::make('completed')->undetermined(),
        ]);
    }
    
    public function gdoTable() : GDO
    {
        return GDO_Todo::table();
    }
    
    public function getQuery() : Query
    {
        $query = parent::getQuery();
        if (!$this->gdoParameterValue('deleted'))
        {
            $query->where("todo_deleted IS NULL");
        }
        if (!$this->gdoParameterValue('completed'))
        {
            $query->where("todo_completed IS NULL");
        }
        return $query;
    }

}
