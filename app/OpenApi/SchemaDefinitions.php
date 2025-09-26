<?php

namespace App\OpenApi;

/**
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
 */
class Organization {}

/**
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
 */
class Building {}

/**
 * @OA\Schema(
 *     schema="OrganizationPhone",
 *     required={"number"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="organization_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="number", type="string", example="+7 (999) 123-45-67"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class OrganizationPhone {}

/**
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
class Activity {}
