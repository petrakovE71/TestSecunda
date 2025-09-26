<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class OrganizationController extends Controller
{
//    /**
//     * Display a listing of the resource.
//     *
//     * @OA\Get(
//     *     path="/api/organizations",
//     *     summary="Get all organizations",
//     *     description="Returns a list of all organizations with their related data",
//     *     operationId="getOrganizations",
//     *     tags={"Organizations"},
//     *     @OA\Response(
//     *         response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(
//     *             type="array",
//     *             @OA\Items(ref="#/components/schemas/Organization")
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=401,
//     *         description="Unauthorized - Invalid API key"
//     *     ),
//     *     security={{"ApiKeyAuth": {}}}
//     * ) *
//     */
//    public function index()
//    {
//        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])->get();
//        return response()->json($organizations);
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @OA\Post(
//     *     path="/api/organizations",
//     *     summary="Create a new organization",
//     *     description="Creates a new organization with the provided data",
//     *     operationId="storeOrganization",
//     *     tags={"Organizations"},
//     *     @OA\RequestBody(
//     *         required=true,
//     *         description="Organization data",
//     *         @OA\JsonContent(
//     *             required={"name", "building_id", "phone_numbers", "activities"},
//     *             @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
//     *             @OA\Property(property="building_id", type="integer", example=1),
//     *             @OA\Property(
//     *                 property="phone_numbers",
//     *                 type="array",
//     *                 @OA\Items(type="string", example="+7 (999) 123-45-67")
//     *             ),
//     *             @OA\Property(
//     *                 property="activities",
//     *                 type="array",
//     *                 @OA\Items(type="integer", example=1)
//     *             )
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=201,
//     *         description="Organization created successfully",
//     *         @OA\JsonContent(ref="#/components/schemas/Organization")
//     *     ),
//     *     @OA\Response(
//     *         response=422,
//     *         description="Validation error"
//     *     ),
//     *     @OA\Response(
//     *         response=401,
//     *         description="Unauthorized - Invalid API key"
//     *     ),
//     *     security={{"ApiKeyAuth": {}}}
//     * ) *
//     */
//    public function store(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'building_id' => 'required|exists:buildings,id',
//            'phone_numbers' => 'required|array',
//            'phone_numbers.*' => 'required|string|max:255',
//            'activities' => 'required|array',
//            'activities.*' => 'required|exists:activities,id',
//        ]);
//
//        $organization = Organization::create([
//            'name' => $request->name,
//            'building_id' => $request->building_id,
//        ]);
//
//        foreach ($request->phone_numbers as $number) {
//            $organization->phoneNumbers()->create(['number' => $number]);
//        }
//
//        $organization->activities()->attach($request->activities);
//
//        return response()->json($organization->load(['building', 'phoneNumbers', 'activities']), 201);
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @OA\Get(
//     *     path="/api/organizations/{id}",
//     *     summary="Get organization by ID",
//     *     description="Returns a single organization by ID with related data",
//     *     operationId="getOrganizationById",
//     *     tags={"Organizations"},
//     *     @OA\Parameter(
//     *         name="id",
//     *         in="path",
//     *         description="ID of organization to return",
//     *         required=true,
//     *         @OA\Schema(
//     *             type="integer",
//     *             format="int64"
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(ref="#/components/schemas/Organization")
//     *     ),
//     *     @OA\Response(
//     *         response=404,
//     *         description="Organization not found"
//     *     ),
//     *     @OA\Response(
//     *         response=401,
//     *         description="Unauthorized - Invalid API key"
//     *     ),
//     *     security={{"ApiKeyAuth": {}}}
//     * ) *
//     */
//    public function show(string $id)
//    {
//        $organization = Organization::with(['building', 'phoneNumbers', 'activities'])->findOrFail($id);
//        return response()->json($organization);
//    }

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
     *     summary="Search organizations by location",
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
    public function byLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required_without_all:min_lat,max_lat,min_lng,max_lng|numeric',
            'min_lat' => 'required_without:radius|numeric',
            'max_lat' => 'required_without:radius|numeric',
            'min_lng' => 'required_without:radius|numeric',
            'max_lng' => 'required_without:radius|numeric',
        ]);

        $query = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->join('buildings', 'organizations.building_id', '=', 'buildings.id');

        if ($request->has('radius')) {
            // Haversine formula to calculate distance
            $haversine = "(
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(buildings.latitude)) *
                    cos(radians(buildings.longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(buildings.latitude))
                )
            )";

            $query->selectRaw("organizations.*, {$haversine} AS distance", [
                $request->latitude,
                $request->longitude,
                $request->latitude
            ])
            ->having('distance', '<=', $request->radius)
            ->orderBy('distance');
        } else {
            // Rectangular area
            $query->whereBetween('buildings.latitude', [$request->min_lat, $request->max_lat])
                ->whereBetween('buildings.longitude', [$request->min_lng, $request->max_lng]);
        }

        $organizations = $query->get();

        return response()->json($organizations);
    }
//
//    /**
//     * Search organizations by activity (including nested activities).
//     *
//     * @OA\Get(
//     *     path="/api/activities/{activityId}/organizations/recursive",
//     *     summary="Get organizations by activity ID (including child activities)",
//     *     description="Returns a list of organizations with a specific activity or any of its child activities",
//     *     operationId="getOrganizationsByActivityRecursive",
//     *     tags={"Organizations"},
//     *     @OA\Parameter(
//     *         name="activityId",
//     *         in="path",
//     *         description="ID of activity to get organizations for",
//     *         required=true,
//     *         @OA\Schema(
//     *             type="integer",
//     *             format="int64"
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(
//     *             type="array",
//     *             @OA\Items(ref="#/components/schemas/Organization")
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=404,
//     *         description="Activity not found"
//     *     ),
//     *     @OA\Response(
//     *         response=401,
//     *         description="Unauthorized - Invalid API key"
//     *     ),
//     *     security={{"ApiKeyAuth": {}}}
//     * ) *
//     */
//    public function searchByActivity(string $activityId)
//    {
//        // Get the activity and all its descendants
//        $activity = Activity::findOrFail($activityId);
//        $activityIds = [$activity->id];
//
//        // Get all child activities recursively
//        $this->getChildActivityIds($activity, $activityIds);
//
//        $organizations = Organization::with(['building', 'phoneNumbers'])
//            ->whereHas('activities', function ($query) use ($activityIds) {
//                $query->whereIn('activities.id', $activityIds);
//            })
//            ->get();
//
//        return response()->json($organizations);
//    }
//
//    /**
//     * Helper method to get all child activity IDs recursively.
//     */
//    private function getChildActivityIds(Activity $activity, array &$ids)
//    {
//        foreach ($activity->children as $child) {
//            $ids[] = $child->id;
//            $this->getChildActivityIds($child, $ids);
//        }
//    }
//
//    /**
//     * Search organizations by name.
//     *
//     * @OA\Post(
//     *     path="/api/organizations/search/name",
//     *     summary="Search organizations by name",
//     *     description="Returns organizations matching the provided name",
//     *     operationId="searchOrganizationsByName",
//     *     tags={"Organizations"},
//     *     @OA\RequestBody(
//     *         required=true,
//     *         description="Search parameters",
//     *         @OA\JsonContent(
//     *             required={"name"},
//     *             @OA\Property(property="name", type="string", example="Рога")
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(
//     *             type="array",
//     *             @OA\Items(ref="#/components/schemas/Organization")
//     *         )
//     *     ),
//     *     @OA\Response(
//     *         response=422,
//     *         description="Validation error"
//     *     ),
//     *     @OA\Response(
//     *         response=401,
//     *         description="Unauthorized - Invalid API key"
//     *     ),
//     *     security={{"ApiKeyAuth": {}}}
//     * ) *
//     */
//    public function searchByName(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string|min:2',
//        ]);
//
//        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
//            ->where('name', 'like', '%' . $request->name . '%')
//            ->get();
//
//        return response()->json($organizations);
//    }
}
