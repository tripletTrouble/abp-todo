<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Services\ApiResponse;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activity_groups = Todo::when(
            $request->has('activity_group_id'),
            fn(Builder $query) => $query->where('activity_group_id', $request->activity_group_id)
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
            'activity_group_id' => ['nullable', 'exists:activity_groups,id', 'integer', 'min:0', 'bail'],
            'title' => ['required', 'string', 'max:255', 'bail'],
            'is_active' => ['nullable', 'boolean', 'bail'],
            'priority' => ['nullable', Rule::in(['very-high', 'high', 'medium', 'low', 'very-low']), 'string']
        ]);

        if ($validator->fails()) {
            $errors = array_values($validator->errors()->toArray());

            return response()->json((new ApiResponse(message: $errors[0][0], status: 'Bad Request')), 400);
        }

        $validated = $validator->validated();

        $todo = new Todo;
        $todo->title = $validated['title'];
        $todo->is_active = $validated['is_active'] ?? true;
        $todo->activity_group_id = $validated['activity_group_id'];
        $todo->priority = $validated['priority'] ?? 'very-high';
        $todo->save();

        return response()->json((new ApiResponse(data: $todo)), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Todo = Todo::find($id);

        if ($Todo) {
            return response()->json((new ApiResponse(data: $Todo)));
        }

        return response()->json((new ApiResponse(status: 'Not Found', message: "Todo with ID {$id} Not Found")), 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::find($id);

        if (empty ($todo)) {
            return response()->json((new ApiResponse(status: 'Not Found', message: "Todo with ID {$id} Not Found")), 404);
        }

        $validator = validator($request->all(), [
            'activity_group_id' => ['nullable', 'exists:activity_groups,id', 'integer', 'min:0', 'bail'],
            'title' => ['nullable', 'string', 'max:255', 'bail'],
            'is_active' => ['nullable', 'boolean', 'bail'],
            'priority' => ['nullable', Rule::in(['very-high', 'high', 'medium', 'low', 'very-low']), 'string']
        ]);

        if ($validator->fails()) {
            $errors = array_values($validator->errors()->toArray());

            return response()->json((new ApiResponse(message: $errors[0][0], status: 'Bad Request')), 400);
        }

        $validated = $validator->validated();

        $todo->title = $validated['title'] ?? $todo->id;
        $todo->is_active = $validated['is_active'] ?? $todo->is_active;
        $todo->activity_group_id = $validated['activity_group_id'] ?? $todo->activity_group_id;
        $todo->priority = $validated['priority'] ?? $todo->priority;
        $todo->save();

        return response()->json((new ApiResponse(data: $todo)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if (empty ($todo)) {
            return response()->json((new ApiResponse(status: 'Not Found', message: "Todo with ID {$id} Not Found")), 404);
        }

        $todo->delete();

        return response()->json((new ApiResponse(data: new \stdClass())));;
    }
}
