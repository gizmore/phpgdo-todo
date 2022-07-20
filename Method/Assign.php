<?php
namespace GDO\Todo\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Form\GDT_Submit;
use GDO\Todo\GDT_Todo;
use GDO\Todo\GDO_Todo;
use GDO\User\GDO_User;
use GDO\User\GDT_User;
use GDO\Date\Time;
use GDO\Form\GDT_Validator;
use GDO\Todo\Module_Todo;

/**
 * Assign a todo item to a user.
 * @author gizmore
 */
final class Assign extends MethodForm
{
    public function isGuestAllowed() : bool
    {
        return Module_Todo::instance()->cfgAddGuests();
    }
    
    public function createForm(GDT_Form $form) : void
    {
        $form->addFields(
            GDT_Todo::make('id')->label('id'),
            GDT_Validator::make('already_completed')->validator('id', [Completed::make(), 'validateAlreadyCompleted']),
            GDT_User::make('to')->label('to')->fallbackCurrentUser(),
            GDT_Validator::make('can_assign')->validator('to', [$this, 'validateCanAssign']),
        );
        $form->actions()->addField(GDT_Submit::make());
    }
    
    public function validateCanAssign(GDT_Form $form, GDT_User $field, GDO_User $value=null)
    {
        if ( ($value) && ($todo = $this->getToDo()) )
        {
            if ($todo->getCreator() === $value)
            {
                return true;
            }
            if ($value->isStaff())
            {
                return true;
            }
            return $field->error('err_todo_assign', [$value->displayName()]);
        }
        return true;
    }
    
    /**
     * @return GDO_Todo
     */
    public function getToDo()
    {
        return $this->getForm()->getFormValue('id');
    }
    
    /**
     * @return GDO_User
     */
    public function getUser()
    {
        $form = $this->getForm();
        if (!($user = $form->getFormValue('to')))
        {
            $user = GDO_User::current();
        }
        return $user;
    }
    
    public function formValidated(GDT_Form $form)
    {
        $user = $this->getUser();
        $todo = $this->getToDo();
        $todo->saveVars([
            'todo_assigned' => Time::getDate(),
            'todo_assignee' => $user->getID(),
        ]);
        return $this->message('msg_todo_assigned', [
            $todo->getID(), $user->renderUserName()]);
    }
    
}
