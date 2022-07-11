<?php
namespace GDO\Todo;

use GDO\Core\GDT_Enum;

/**
 * A todo priority selection.
 * @author gizmore
 */
final class GDT_TodoPriority extends GDT_Enum
{
    # Priorities
    const LOW = 'low';
    const MEDIUM = 'medium';
    const HIGH = 'high';
    
    public function defaultLabel() : self { return $this->label('priority'); }
    
    # GDT
    protected function __construct()
    {
        parent::__construct();
        $this->enumValues(self::LOW, self::MEDIUM, self::HIGH);
        $this->notNull();
        $this->initial(self::LOW);
    }

}
