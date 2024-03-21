<?php

namespace App\Http\Controllers;

use App\Models\ActivityGroup;
use App\Services\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActivityGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activity_groups = ActivityGroup::when(
            $request->has('email'),
            fn(Builder $query) => $query->where('email', $request->email)
        )
            ->get();

        return response()->json((new ApiResponse(data: $activity_groups)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'title' => ['required', 'string', 'max:255', 'bail'],
            'email' => ['nullable', 'string', 'max:255', 'email']
        ]);

        if ($validator->fails()) {
            $errors = array_values($validator->errors()->toArray());

            return response()->json((new ApiResponse(message: $errors[0][0], status: 'Bad Request')), 400);
        }

        $validated = $validator->validated();

        $activity_group = new ActivityGroup;
        $activity_group->title = $validated['title'];
        $activity_group->email = $validated['email'] ?? 'null';
        $activity_group->save();

        return response()->json((new ApiResponse(data: $activity_group)), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activityGroup = ActivityGroup::find($id);

        if ($activityGroup) {
            return response()->json((new ApiResponse(data: $activityGroup)));
        }

        return response()->json((new ApiResponse(status: 'Not Found', message: "Activity with ID {$id} Not Found")), 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activityGroup = ActivityGroup::find($id);

        if (empty ($activityGroup)) {
            return response()->json((new ApiResponse(status: 'Not Found', message: "Activity with ID {$id} Not Found")), 404);
        }

        $validator = validator($request->all(), [
            'title' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255', 'email']
        ]);

        if ($validator->fails()) {
            $errors = array_values($validator->errors()->toArray());

            return response()->json((new ApiResponse(message: $errors[0][0], status: 'Bad Request')), 400);
        }

        $validated = $validator->validated();

        $activityGroup->title = $validated['title'] ?? $activityGroup->title;
        $activityGroup->email = $validated['email'] ?? $activityGroup->email;
        $activityGroup->save();

        return response()->json((new ApiResponse(data: $activityGroup)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityGroup = ActivityGroup::find($id);

        if (empty ($activityGroup)) {
            return response()->json((new ApiResponse(status: 'Not Found', message: "Activity with ID {$id} Not Found")), 404);
        }

        $activityGroup->delete();

        return response()->json((new ApiResponse(data: new \stdClass())));;
    }
}
