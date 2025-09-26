<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationSearchRequest;
use App\Http\Requests\OrganizationNameSearchRequest;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use App\Services\OrganizationLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class OrganizationController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Вывод информации об организации по её идентификатору",
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
     * ) *
     */
    public function show(string $id)
    {
        $organization = Organization::with(['building', 'phoneNumbers', 'activities'])->findOrFail($id);
        return response()->json($organization);
    }

    /**
     * List all organizations in a specific building.
     *
     * @OA\Get(
     *     path="/api/buildings/{buildingId}/organizations",
     *     summary="Список всех организаций находящихся в конкретном здании",
     *     description="Returns a list of organizations in a specific building",
     *     operationId="getOrganizationsByBuilding",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         description="ID of building to get organizations for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Building not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * ) *
     */
    public function byBuilding(string $buildingId)
    {
        $organizations = Organization::with(['phoneNumbers', 'activities'])
            ->where('building_id', $buildingId)
            ->get();

        return response()->json($organizations);
    }

    /**
     * List all organizations related to a specific activity.
     *
     * @OA\Get(
     *     path="/api/activities/{activityId}/organizations",
     *     summary="Список всех организаций, которые относятся к указанному виду деятельности",
     *     description="Returns a list of organizations with a specific activity",
     *     operationId="getOrganizationsByActivity",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         description="ID of activity to get organizations for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Activity not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * ) *
     */
    public function byActivity(string $activityId)
    {
        $organizations = Organization::with(['building', 'phoneNumbers'])
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where('activities.id', $activityId);
            })
            ->get();

        return response()->json($organizations);
    }

    /**
     * List organizations within a radius/rectangular area.
     *
     * @OA\Post(
     *     path="/api/organizations/search/location",
     *     summary="список организаций, которые находятся в заданном радиусе/прямоугольной области относительно указанной точки на карте. список зданий",
     *     description="список организаций, которые находятся в заданном радиусе/прямоугольной области относительно указанной точки на карте. список зданий",
     *     operationId="searchOrganizationsByLocation",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Search parameters",
     *         @OA\JsonContent(
     *             required={"latitude", "longitude"},
     *             @OA\Property(property="latitude", type="number", format="float", example=55.7558),
     *             @OA\Property(property="longitude", type="number", format="float", example=37.6173),
     *             @OA\Property(property="radius", type="number", format="float", example=1.5),
     *             @OA\Property(property="min_lat", type="number", format="float", example=55.7),
     *             @OA\Property(property="max_lat", type="number", format="float", example=55.8),
     *             @OA\Property(property="min_lng", type="number", format="float", example=37.5),
     *             @OA\Property(property="max_lng", type="number", format="float", example=37.7)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * ) *
     */
    public function byLocation(LocationSearchRequest $request, OrganizationLocationService $locationService)
    {
        $organizations = $locationService->findByLocation($request->validated());

        return response()->json($organizations);
    }

    /**
     * Search organizations by activity (including nested activities).
     *
     * @OA\Get(
     *     path="/api/activities/{activityId}/organizations/recursive",
     *     summary="искать организации по виду деятельности. Например, поиск по виду деятельности «Еда», которая находится на первом уровне дерева, и чтобы нашлись все организации, которые относятся к видам деятельности, лежащим внутри. Т.е. в результатах поиска должны отобразиться организации с видом деятельности Еда, Мясная продукция, Молочная продукция.",
     *     description="Returns a list of organizations with a specific activity or any of its child activities",
     *     operationId="getOrganizationsByActivityRecursive",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         description="ID of activity to get organizations for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Activity not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * ) *
     */
    public function searchByActivity(string $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $activityIds = [$activity->id];

        $this->getChildActivityIds($activity, $activityIds);

        $organizations = Organization::with(['building', 'phoneNumbers'])
            ->whereHas('activities', function ($query) use ($activityIds) {
                $query->whereIn('activities.id', $activityIds);
            })
            ->get();

        return response()->json($organizations);
    }

    /**
     * Helper method to get all child activity IDs recursively.
     */
    private function getChildActivityIds(Activity $activity, array &$ids)
    {
        foreach ($activity->children as $child) {
            $ids[] = $child->id;
            $this->getChildActivityIds($child, $ids);
        }
    }

    /**
     * Search organizations by name.
     *
     * @OA\Post(
     *     path="/api/organizations/search/name",
     *     summary="Поиск организации по названию",
     *     description="Returns organizations matching the provided name",
     *     operationId="searchOrganizationsByName",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Search parameters",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Продукт")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * ) *
     */
    public function searchByName(OrganizationNameSearchRequest $request)
    {
        $searchTerm = '%' . $request->name . '%';
        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->where('name', 'like', $searchTerm)
            ->get();

        return response()->json($organizations);
    }
}
