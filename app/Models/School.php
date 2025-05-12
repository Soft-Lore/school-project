<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'subdomain',
        'database_name',
        'db_host',
        'db_port',
        'db_username',
        'db_password',
    ];
}
