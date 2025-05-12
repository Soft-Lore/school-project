<?php

namespace App\Services;

use App\Actions\School\CreateAdminUserAction;
use App\DTOs\School\CreateSchoolDto;
use App\Models\School;
use App\Actions\School\CreateSchoolAction;
use App\Actions\School\LoginUserAction;
use App\DTOs\School\LoginSchoolDto;
use Illuminate\Auth\Events\Login;

class SchoolService
{
    public function __construct(
        protected CreateSchoolAction $createSchoolAction,
        protected CreateAdminUserAction $createAdminUserAction,
    ) {}

    public function registerSchool(CreateSchoolDto $dto): School
    {
        $school = $this->createSchoolAction->execute($dto);

        $this->createAdminUserAction->execute(
            $dto->admin_email,
            $dto->admin_password
        );

        return $school;
    }
}
