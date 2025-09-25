<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])->get();
        return response()->json($organizations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
            'phone_numbers' => 'required|array',
            'phone_numbers.*' => 'required|string|max:255',
            'activities' => 'required|array',
            'activities.*' => 'required|exists:activities,id',
        ]);

        $organization = Organization::create([
            'name' => $request->name,
            'building_id' => $request->building_id,
        ]);

        foreach ($request->phone_numbers as $number) {
            $organization->phoneNumbers()->create(['number' => $number]);
        }

        $organization->activities()->attach($request->activities);

        return response()->json($organization->load(['building', 'phoneNumbers', 'activities']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = Organization::with(['building', 'phoneNumbers', 'activities'])->findOrFail($id);
        return response()->json($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'building_id' => 'sometimes|required|exists:buildings,id',
            'phone_numbers' => 'sometimes|required|array',
            'phone_numbers.*' => 'required|string|max:255',
            'activities' => 'sometimes|required|array',
            'activities.*' => 'required|exists:activities,id',
        ]);

        $organization = Organization::findOrFail($id);

        if ($request->has('name')) {
            $organization->name = $request->name;
        }

        if ($request->has('building_id')) {
            $organization->building_id = $request->building_id;
        }

        $organization->save();

        if ($request->has('phone_numbers')) {
            $organization->phoneNumbers()->delete();
            foreach ($request->phone_numbers as $number) {
                $organization->phoneNumbers()->create(['number' => $number]);
            }
        }

        if ($request->has('activities')) {
            $organization->activities()->sync($request->activities);
        }

        return response()->json($organization->load(['building', 'phoneNumbers', 'activities']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return response()->json(null, 204);
    }

    /**
     * List all organizations in a specific building.
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

    /**
     * Search organizations by activity (including nested activities).
     */
    public function searchByActivity(string $activityId)
    {
        // Get the activity and all its descendants
        $activity = Activity::findOrFail($activityId);
        $activityIds = [$activity->id];

        // Get all child activities recursively
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
     */
    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2',
        ]);

        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();

        return response()->json($organizations);
    }
}
