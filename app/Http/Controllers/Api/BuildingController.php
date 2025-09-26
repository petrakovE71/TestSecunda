<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="Get all buildings",
     *     description="Returns a list of all buildings with their related data",
     *     operationId="getBuildings",
     *     tags={"Buildings"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Building")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid API key"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * )
     * **/
    public function index()
    {
        $buildings = Building::with('organizations')->get();
        return response()->json($buildings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/buildings",
     *     summary="Create a new building",
     *     description="Creates a new building with the provided data",
     *     operationId="storeBuilding",
     *     tags={"Buildings"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Building data",
     *         @OA\JsonContent(
     *             required={"address", "latitude", "longitude"},
     *             @OA\Property(property="address", type="string", example="ул. Ленина, 1"),
     *             @OA\Property(property="latitude", type="number", format="float", example=55.7558),
     *             @OA\Property(property="longitude", type="number", format="float", example=37.6173)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Building created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Building")
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
     *                     property="address",
     *                     type="array",
     *                     @OA\Items(type="string", example="The address field is required.")
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
     *  **/
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $building = Building::create($request->all());
        return response()->json($building, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/buildings/{id}",
     *     summary="Get building by ID",
     *     description="Returns a single building by ID with related data",
     *     operationId="getBuildingById",
     *     tags={"Buildings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of building to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Building")
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
     * )  *
     */
    public function show(string $id)
    {
        $building = Building::with('organizations')->findOrFail($id);
        return response()->json($building);
    }
}
