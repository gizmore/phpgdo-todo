<?php
namespace GDO\Todo\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Todo\GDO_Todo;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Submit;
use GDO\Todo\Module_Todo;

/**
 * Add a todo entry.
 * @author gizmore
 */
final class Add extends MethodForm
{
    public function isGuestAllowed() : bool
    {
        return Module_Todo::instance()->cfgAddGuests();
    }
    
    public function createForm(GDT_Form $form) : void
    {
        $table = GDO_Todo::table();
        $form->addFields(
            $table->gdoColumn('todo_text'),
            $table->gdoColumn('todo_priority'),
            $table->gdoColumn('todo_description'),
            GDT_AntiCSRF::make(),
        );
        $form->actions()->addField(GDT_Submit::make());
    }
    
    public function formValidated(GDT_Form $form)
    {
        $todo = GDO_Todo::blank($form->getFormVars())->insert();
        return $this->messageCreated($todo, true);
    }
    
}
