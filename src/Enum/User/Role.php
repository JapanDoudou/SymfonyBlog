<?php

namespace App\Enum\User;

use App\Enum\EnumString;

enum Role : string
{
	use EnumString;
	case ROLE_ADMIN = 'ROLE_ADMIN';
	case ROLE_USER = 'ROLE_USER';
}
