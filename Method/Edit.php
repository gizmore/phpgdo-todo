<?php
namespace GDO\Todo\Method;

use GDO\Core\GDO;
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
    public function gdoTable() : GDO
    {
        return GDO_Todo::table();
    }

    public function canCreate(GDO $table)
    {
        return false;
    }
    
    public function hrefList()
    {
        return href('Todo', 'Search');
    }

    public function createForm(GDT_Form $form) : void
    {
        parent::createForm($form);
        $form->getField('todo_text')->notNull(false);
    }
    
}
