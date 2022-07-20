<?php
namespace GDO\Todo;

use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\UI\GDT_Message;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\DB\GDT_DeletedAt;
use GDO\DB\GDT_DeletedBy;
use GDO\Date\GDT_DateTime;
use GDO\Date\Time;
use GDO\User\GDT_User;
use GDO\Core\GDT_EditedAt;
use GDO\DB\GDT_EditedBy;
use GDO\Core\GDT_Template;
use GDO\User\GDO_User;
use GDO\Core\GDT_Name;
use GDO\UI\GDT_Title;

/**
 * ToDo database table/entity.
 * @author gizmore
 * @version 6.10.4
 */
final class GDO_Todo extends GDO
{
    ###########
    ### GDO ###
    ###########
    public function gdoColumns() : array
    {
        return [
            GDT_AutoInc::make('todo_id'),
            GDT_Title::make('todo_text')->notNull()->label('text'),
            GDT_TodoPriority::make('todo_priority')->label('priority'),
            GDT_Message::make('todo_description')->label('description'),
            GDT_CreatedAt::make('todo_created'),
            GDT_CreatedBy::make('todo_creator'),
            GDT_EditedAt::make('todo_edited'),
            GDT_EditedBy::make('todo_editor'),
            GDT_DateTime::make('todo_assigned')->label('assigned_on'),
            GDT_User::make('todo_assignee')->label('to'),
            GDT_DateTime::make('todo_completed')->label('completed_on'),
            GDT_User::make('todo_completor')->label('by'),
            GDT_DeletedAt::make('todo_deleted'),
            GDT_DeletedBy::make('todo_deletor'),
        ];
    }

    ###############
    ### Getters ###
    ###############
    /**
     * @return GDO_User
     */
    public function getCreator() { return $this->gdoValue('todo_creator'); }
    public function getCreatorID() { return $this->gdoVar('todo_creator'); }
    public function isAssigned() { return $this->gdoVar('todo_assigned') !== null; }
    public function isCompleted() { return $this->getCompleted() !== null; }
    public function getCompleted() { return $this->gdoVar('todo_completed'); }
    
    ##############
    ### Render ###
    ##############
    public function displayTitle()
    {
        return $this->display('todo_text');
    }
    
    public function displayCompleted()
    {
        return Time::displayDate($this->getCompleted(), Time::FMT_SHORT);
    }
    
    public function renderCard() : string
    {
        return GDT_Template::templatePHP(
            'Todo', 'todo_card.php',
            ['todo' => $this]);
    }
    
    public function renderList() : string
    {
        return GDT_Template::php(
            'Todo', 'todo_listitem.php',
            ['todo' => $this]);
    }
    
}
