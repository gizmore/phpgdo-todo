<?php
namespace GDO\Todo;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\UI\GDT_Page;
use GDO\UI\GDT_Link;

/**
 * A todo database.
 * 
 * @author gizmore
 * @version 7.0.0
 * @since 6.11.3
 */
final class Module_Todo extends GDO_Module
{
    public int $priority = 80;

    ##############
    ### Module ###
    ##############
    public function onLoadLanguage() : void
    {
        $this->loadLanguage('lang/todo');
    }
    
    public function getClasses() : array
    {
        return [
            GDO_Todo::class,
        ];
    }
    
    ##############
    ### Config ###
    ##############
    public function getConfig() : array
    {
        return [
            GDT_Checkbox::make('todo_left_bar')->initial('1'),
            GDT_Checkbox::make('todo_add_guests')->initial('1'),
        ];
    }
    public function cfgSidebar() : string { return $this->getConfigVar('todo_left_bar'); }
    public function cfgAddGuests() : string { return $this->getConfigVar('todo_add_guests'); }
    
    ############
    ### Init ###
    ############
    public function onIncludeScripts() : void
    {
    }
    
    public function onInitSidebar() : void
    {
        if ($this->cfgSidebar())
        {
            GDT_Page::instance()->leftBar()->addFields(
                GDT_Link::make('link_todo_add')->icon('create')->href(href('Todo', 'Add')),
            	GDT_Link::make('mt_todo_search')->icon('search')->href(href('Todo', 'Search')),
            );
        }
    }
    
}
