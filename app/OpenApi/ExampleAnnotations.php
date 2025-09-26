<?php

namespace App\OpenApi;

/**
 * This file contains example OpenAPI annotations for reference.
 * It is not used in the application but serves as a guide for adding annotations to controllers.
 */

/**
 * @OA\Info(
 *     title="Organization Directory API",
 *     version="1.0.0",
 *     description="API for managing organizations, buildings, and activities",
 *     @OA\Contact(
 *         email="admin@example.com",
 *         name="API Support"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="ApiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )
 */

/**
 * Example controller class annotations
 *
 * @OA\Tag(
 *     name="Organizations",
 *     description="API Endpoints for Organizations"
 * )
 */
class ExampleOrganizationController
{
    /**
     * Example of a GET endpoint annotation
     *
     * @OA\Get(
     *     path="/api/organizations",
     *     summary="Get all organizations",
     *     description="Returns a list of all organizations with their related data",
     *     operationId="getOrganizations",
     *     tags={"Organizations"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * )
     */
    public function index()
    {
        // Method implementation
    }

    /**
     * Example of a POST endpoint annotation
     *
     * @OA\Post(
     *     path="/api/organizations",
     *     summary="Create a new organization",
     *     description="Creates a new organization with the provided data",
     *     operationId="storeOrganization",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Organization data",
     *         @OA\JsonContent(
     *             required={"name", "building_id", "phone_numbers", "activities"},
     *             @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
     *             @OA\Property(property="building_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="phone_numbers",
     *                 type="array",
     *                 @OA\Items(type="string", example="+7 (999) 123-45-67")
     *             ),
     *             @OA\Property(
     *                 property="activities",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Organization created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * )
     */
    public function store()
    {
        // Method implementation
    }

    /**
     * Example of a GET endpoint with parameter annotation
     *
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Get organization by ID",
     *     description="Returns a single organization by ID with related data",
     *     operationId="getOrganizationById",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of organization to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * )
     */
    public function show($id)
    {
        // Method implementation
    }
}

/**
 * Example schema definitions
 *
 * @OA\Schema(
 *     schema="Organization",
 *     required={"name", "building_id"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
 *     @OA\Property(property="building_id", type="integer", format="int64", example=1),
 *     @OA\Property(
 *         property="building",
 *         ref="#/components/schemas/Building"
 *     ),
 *     @OA\Property(
 *         property="phone_numbers",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrganizationPhone")
 *     ),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Activity")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Building",
 *     required={"address", "latitude", "longitude"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="address", type="string", example="ул. Ленина, 1"),
 *     @OA\Property(property="latitude", type="number", format="float", example=55.7558),
 *     @OA\Property(property="longitude", type="number", format="float", example=37.6173),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="OrganizationPhone",
 *     required={"number"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="organization_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="number", type="string", example="+7 (999) 123-45-67"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Activity",
 *     required={"name", "level"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Еда"),
 *     @OA\Property(property="parent_id", type="integer", format="int64", nullable=true, example=null),
 *     @OA\Property(property="level", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
