<?php

namespace App\Http\Controllers\Api;

use App\DTOs\School\CreateSchoolDto;
use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(
        protected SchoolService $schoolService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/schools/register",
     *     summary="Registrar una nueva escuela",
     *     tags={"Escuelas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "admin_email", "admin_password"},
     *             @OA\Property(property="name", type="string", example="Colegio Bautista"),
     *             @OA\Property(property="admin_email", type="string", example="admin@colegio.com"),
     *             @OA\Property(property="admin_password", type="string", example="securePass123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Escuela registrada correctamente"
     *     )
     * )
     */
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        $dto = CreateSchoolDto::fromArray($request->only(['name', 'admin_email', 'admin_password']));

        $school = $this->schoolService->registerSchool($dto);

        return response()->json([
            'message' => 'Escuela registrada correctamente',
            'subdomain' => $school->subdomain,
            'database' => $school->database_name,
            'login_url' => "https://{$school->subdomain}.tusistema.com",
        ], 201);
    }

}
