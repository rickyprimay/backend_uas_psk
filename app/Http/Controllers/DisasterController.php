<?php

namespace App\Http\Controllers;

use App\Models\Disaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DisasterController extends Controller
{
    public function index()
    {
        $disasters = Disaster::all();
        return response()->json([
            'status' => true,
            'message' => 'Disasters retrieved successfully',
            'data' => $disasters
        ]);
    }

    public function show($id)
    {
        $disaster = Disaster::find($id);
        if (!$disaster) {
            return response()->json([
                'status' => false,
                'message' => 'Disaster not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Disaster retrieved successfully',
            'data' => $disaster
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'required|string|max:1000',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $authorName = Auth::user()->name;

        $disaster = new Disaster();
        $disaster->title = $request->title;
        $disaster->content = $request->content;
        $disaster->description = $request->description;
        $disaster->location = $request->location;
        $disaster->author = $authorName;
        

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('disasters', 'public');
            $disaster->image = $path;
        }

        $disaster->save();

        return response()->json([
            'status' => true,
            'message' => 'Disaster created successfully',
            'data' => $disaster
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $disaster = Disaster::find($id);

        if (!$disaster) {
            return response()->json([
                'status' => false,
                'message' => 'Disaster not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'required|string|max:1000',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $disaster->title = $request->title;
        $disaster->content = $request->content;
        $disaster->description = $request->description;
        $disaster->location = $request->location;

        if ($request->hasFile('image')) {
            if ($disaster->image) {
                Storage::disk('public')->delete($disaster->image);
            }

            $path = $request->file('image')->store('disasters', 'public');
            $disaster->image = $path;
        }

        $disaster->save();

        return response()->json([
            'status' => true,
            'message' => 'Disaster updated successfully',
            'data' => $disaster
        ]);
    }

    public function destroy($id)
    {
        $disaster = Disaster::find($id);

        if (!$disaster) {
            return response()->json([
                'status' => false,
                'message' => 'Disaster not found',
            ], 404);
        }

        if ($disaster->image) {
            Storage::disk('public')->delete($disaster->image);
        }

        $disaster->delete();

        return response()->json([
            'status' => true,
            'message' => 'Disaster deleted successfully',
        ]);
    }
}
