<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with(['parent', 'children', 'organizations'])->get();
        return response()->json($activities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:activities,id',
        ]);

        // Check level constraint
        $level = 1;
        if ($request->parent_id) {
            $parent = Activity::findOrFail($request->parent_id);
            $level = $parent->level + 1;

            if ($level > 3) {
                return response()->json([
                    'message' => 'Maximum nesting level (3) exceeded'
                ], 422);
            }
        }

        $activity = Activity::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'level' => $level,
        ]);

        return response()->json($activity, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activity::with(['parent', 'children', 'organizations'])->findOrFail($id);
        return response()->json($activity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'parent_id' => 'sometimes|nullable|exists:activities,id',
        ]);

        $activity = Activity::findOrFail($id);

        // Check if we're trying to set a new parent
        if ($request->has('parent_id') && $request->parent_id != $activity->parent_id) {
            // Check level constraint
            $level = 1;
            if ($request->parent_id) {
                $parent = Activity::findOrFail($request->parent_id);
                $level = $parent->level + 1;

                // Check if the new parent is not a descendant of this activity
                if ($this->isDescendant($activity->id, $request->parent_id)) {
                    return response()->json([
                        'message' => 'Cannot set a descendant as parent'
                    ], 422);
                }

                if ($level > 3) {
                    return response()->json([
                        'message' => 'Maximum nesting level (3) exceeded'
                    ], 422);
                }
            }

            $activity->parent_id = $request->parent_id;
            $activity->level = $level;

            // Update levels of all descendants
            $this->updateDescendantLevels($activity);
        }

        if ($request->has('name')) {
            $activity->name = $request->name;
        }

        $activity->save();

        return response()->json($activity->load(['parent', 'children']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return response()->json(null, 204);
    }

    /**
     * Get activities in a hierarchical structure.
     */
    public function tree()
    {
        $activities = Activity::with('children')
            ->whereNull('parent_id')
            ->get();

        return response()->json($activities);
    }

    /**
     * Check if one activity is a descendant of another.
     */
    private function isDescendant($activityId, $potentialDescendantId)
    {
        $descendant = Activity::find($potentialDescendantId);

        while ($descendant && $descendant->parent_id) {
            if ($descendant->parent_id == $activityId) {
                return true;
            }
            $descendant = Activity::find($descendant->parent_id);
        }

        return false;
    }

    /**
     * Update the levels of all descendants of an activity.
     */
    private function updateDescendantLevels(Activity $activity)
    {
        foreach ($activity->children as $child) {
            $child->level = $activity->level + 1;
            $child->save();

            $this->updateDescendantLevels($child);
        }
    }
}
