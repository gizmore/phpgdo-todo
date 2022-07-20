<?phpuse GDO\Todo\GDO_Todo;
use GDO\UI\GDT_Card;use GDO\UI\GDT_Title;
/** @var $todo GDO_Todo **/$card = GDT_Card::make()->gdo($todo);$card->creatorHeader(GDT_Title::make()->titleRaw($todo->displayTitle()));$card->editorFooter();$card->addFields(    $todo->gdoColumn('todo_priority'),    $todo->gdoColumn('todo_description'),);echo $card->render();