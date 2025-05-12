<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;

class TenantToken extends PersonalAccessToken
{
    protected $connection = 'tenant';
    protected $table = 'personal_access_tokens';
}
