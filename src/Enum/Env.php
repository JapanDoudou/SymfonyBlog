<?php

namespace App\Enum;

enum Env : string
{
	use EnumString;
	case DEV = 'dev';
	case STAGING = 'staging';
	case TEST = 'test';
	case DEMO = 'demo';
	case PROD = 'prod';

	public static function getCurrentEnv()
	{
		$env = getenv('APP_ENV');
		return self::from($env);
	}
}
