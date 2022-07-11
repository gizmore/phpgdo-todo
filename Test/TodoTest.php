<?php
namespace GDO\Todo\Test;

use GDO\Tests\TestCase;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertStringContainsStringIgnoringCase;
use GDO\Todo\Module_Todo;
use GDO\Table\Module_Table;

/**
 * Test the Todo module.
 * Tests are done using the CLI rendering and method handling.
 * This is a nice test suite for cli behaviour and general form stuff.
 * This is a nice test suite for GDT_Table pagination.
 * 
 * @author gizmore
 * @version 6.10.4
 * @since 6.10.4
 */
final class TodoTest extends TestCase
{
    public function testUsage()
    {
        assertStringContainsStringIgnoringCase(
            "Usage: ",
            $this->cli("todo.add"),
            'Test if help is shown for the todo.add command.'
        );
    }
    
    public function testAdding()
    {
        assertStringContainsStringIgnoringCase(
            "ID: 1",
            $this->cli("todo.add. 'Write a todo plugin for dog.'"),
            'Test if a todo item can be created');
    }
    
    public function testPrioAndDescriptionOnAdding()
    {
        # Add
        $r = $this->cli("todo.add --priority=high '--description=Description test' 'Add priorities and description to ToDo items.'");
        assertStringContainsStringIgnoringCase(
            "ID: 2", $r,
            'Test if a todo item with high prio can be created.');

        # Read
        $r = $this->cli("todo.show 2");
        $this->assertStringContainsStringsIgnoringCase(
            ['ID: 2', 'Priority: High', 'Description test', 'Title: Add priorities and description to ToDo items.'],
            $r, 'Test if todo item with high prio and description can be viewed.');
    }
    
    public function testGuestCannotAdd()
    {
        $this->userGaston(); # switch user to guest
        
        Module_Todo::instance()->saveConfigVar('todo_add_guests', '0');
        $r = $this->cli("todo.add 'This is a test2.'");
        assertStringContainsString('for registered members only.', $r, 'Test if guests cannot add a todo item.');

        Module_Todo::instance()->saveConfigVar('todo_add_guests', '1');
        $r = $this->cli("todo.add 'This is a test3.'");
        assertStringContainsString('ID: 3', $r, 'Test if guests can add a todo item.');
    }
    
    public function testAssign()
    {
        $this->userGaston(); # switch user to guest
      
        $r = $this->cli('todo.assign 3');
        assertStringContainsString('ToDo #3 has been assigned to ~Gaston~.', $r, 'Test if guests can assign to themself');
    
        $r = $this->cli('todo.assign --to=gizmore 3');
        assertStringContainsString('ToDo #3 has been assigned to gizmore.', $r, 'Test if guests can assign to member');
        
        $this->userGizmore();
        $r = $this->cli("todo.add 'This is a test4.'");
        assertStringContainsString('ID: 4', $r, 'Test if 4 todos can be created.');
        
        $this->userGaston();
        $r = $this->cli('todo.assign --to=gizmore 4');
        assertStringContainsString('ToDo #4 has been assigned to gizmore.', $r, 'Test if guests can assign creators to their todos');

        Module_Todo::instance()->saveConfigVar('todo_add_guests', '0');
        $r = $this->cli('todo.assign --to=gizmore 4');
        assertStringContainsString('This functionality is for registered members only.', $r, 'Test if guests cannot assign creators to their todos anymore');

        $this->userGizmore();
        $r = $this->cli('todo.assign --to=gizmore 4');
        assertStringContainsString('ToDo #4 has been assigned to gizmore.', $r, 'Test if members still can assign todo items');

        $this->userSven();
        $r = $this->cli('todo.assign --to=gizmore 4');
        assertStringContainsString('ToDo #4 has been assigned to gizmore.', $r, 'Test if staff also can assign todo items');
    }
    
    public function testEditTodos()
    {
        $this->userGizmore();
        $r = $this->cli('todo.edit.show 4');
        assertStringContainsString('edit', $r, 'Test if gizmore can view his own todo item via the edit method');
        
        $r = $this->cli("todo.edit.edit '--text=Neuer ToDo Text!' 4");
        assertStringContainsString('Your ToDo has been updated.', $r, 'Test if gizmore can view his own todo item via the edit method');
        assertStringContainsString('Text: Neuer ToDo Text!', $r, 'Test if gizmore can view his own todo item via the edit method');
        
        $r = $this->cli('todo.edit 4');
        assertStringContainsString('Neuer ToDo Text!', $r, 'Test if gizmore can view his own todo item via the edit method');

        $r = $this->cli('todo.edit');
        assertStringContainsString('Usage: todo.edit.[create]', $r, 'Test if crud shows usage');
    }
    
    public function testCompletion()
    {
        $this->userGizmore();
        $r = $this->cli('todo.completed 4');
        assertStringContainsString('ToDo #4 has been completed by gizmore on '.date('m'), $r, 'Test if todos can be completed by fallback user');

        $r = $this->cli('todo.completed 4');
        assertStringContainsString('This ToDo item has already been completed.', $r, 'Test if todos can be completed only once');
        
        $r = $this->cli('todo.completed --by=Sven 3');
        assertStringContainsString('ToDo #3 has been completed by Sven', $r, 'Test if todos can be completed by a specified user');
    }
    
    public function testDeletion()
    {
        $r = $this->cli('todo.edit.delete 2');
        assertStringContainsString('Your ToDo has been deleted.', $r, 'Test if todos can be deleted by staff');
        
        $this->userMonica();
        $r = $this->cli('todo.edit.delete 3');
        assertStringContainsString('You do not have permissions to edit this object.', $r, 'Test if todos cannot be deleted by members');
    }
    
    public function testSearch()
    {
    	Module_Table::instance()->saveConfigVar('ipp_cli', '2');
    	Module_Table::instance()->saveConfigVar('ipp_http', '2');
    	
        $r = $this->cli('todo.search');
        assertStringContainsString("Usage: todo.search.", $r, 'Test usage printing for todo search');
        assertStringContainsString("[--page=<1>", $r, 'Test nice usage printing for todo search');
    
        $r = $this->cli('todo.search --deleted=1 --completed=1');
        assertStringContainsString("4 ToDo's. Page 1/2", $r, 'Test nice pagination usage printing for todo search');

        $r = $this->cli('todo.search --completed=1');
        assertStringContainsString("3 ToDo's", $r, 'Test correct method filtering for completed todos');
    
        $r = $this->cli('todo.search --deleted=1');
        assertStringContainsString("2 ToDo's", $r, 'Test correct method filtering for deleted todos');
    
        $r = $this->cli("todo.search '--search=Neuer ToDo Text!'");
        assertStringContainsString("2 ToDo's", $r, 'Test correct method filtering for deleted todos');
    }
    
}
