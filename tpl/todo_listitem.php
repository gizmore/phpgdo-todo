<?phpnamespace GDO\Todo\tpl;use GDO\Table\GDT_ListItem;use GDO\UI\GDT_Title;use GDO\UI\GDT_Container;/** @var $todo \GDO\Todo\GDO_Todo **/$li = GDT_ListItem::make('todo_'.$todo->getID())->gdo($todo);$li->creatorHeader(GDT_Title::make()->titleRaw($todo->displayTitle()));$cont = GDT_Container::make()->vertical();$cont->addField($todo->gdoColumn('todo_description'));$cont->addField($todo->gdoColumn('todo_priority'));$li->content($cont);echo $li->render();