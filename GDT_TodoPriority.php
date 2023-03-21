<?php
namespace GDO\Todo;

use GDO\Core\GDT_Enum;

/**
 * A todo priority selection.
 *
 * @author gizmore
 */
final class GDT_TodoPriority extends GDT_Enum
{

	# Priorities
	public const LOW = 'low';
	public const MEDIUM = 'medium';
	public const HIGH = 'high';

	protected function __construct()
	{
		parent::__construct();
		$this->enumValues(self::LOW, self::MEDIUM, self::HIGH);
		$this->notNull();
		$this->initial(self::LOW);
	}

	# GDT

	public function defaultLabel(): self { return $this->label('priority'); }

}
