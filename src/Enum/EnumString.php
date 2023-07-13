<?php

namespace App\Enum;

use ValueError;

trait EnumString
{
	public static function fromName(string $name): string
	{
		foreach (self::cases() as $status) {
			if( $name === $status->name ){
				return $status->value;
			}
		}
		throw new ValueError("$name is not a valid backing value for enum " . self::class );
	}

	public static function exist(string $value): bool
	{
		$cases = self::cases();
		foreach ($cases as $case)
		{
			if ($value === $case->value)
			{
				return true;
			}
		}
		return false;
	}
}
