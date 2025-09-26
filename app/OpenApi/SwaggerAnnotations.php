<?php

namespace App\OpenApi;

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
 *
 * @OA\Tag(
 *     name="Buildings",
 *     description="API Endpoints for Buildings"
 * )
 *
 * @OA\Tag(
 *     name="Organizations",
 *     description="API Endpoints for Organizations"
 * )
 *
 * @OA\Tag(
 *     name="Activities",
 *     description="API Endpoints for Activities"
 * )
 */
class SwaggerAnnotations
{
    // This class is empty and only serves as a container for Swagger annotations
}
