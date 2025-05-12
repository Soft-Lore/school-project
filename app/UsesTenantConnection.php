<?php

namespace App\Traits;

trait UsesTenantConnection
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'tenant']);
    }
}