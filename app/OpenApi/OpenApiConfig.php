<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="Organization Directory API",
 *     version="1.0.0",
 *     description="API for managing organizations and their relationships with buildings and activities",
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
 *     name="Organizations",
 *     description="API Endpoints for Organizations"
 * )
 *
 * @OA\Tag(
 *     name="Buildings",
 *     description="Organization endpoints related to buildings"
 * )
 *
 * @OA\Tag(
 *     name="Activities",
 *     description="Organization endpoints related to activities"
 * )
 */
class OpenApiConfig
{
    // This class is used only for OpenAPI annotations
}

/**
 * Schema definitions are located in App\OpenApi\SchemaDefinitions.php
 */
