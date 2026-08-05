<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PolicyController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Policy::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $policy = Policy::create([
            'title'   => $request->title,
            'slug'    => $this->generateUniqueSlug($request->title),
            'content' => $request->content,
            'status'  => $request->status ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Policy created successfully.',
            'data'    => $policy,
        ], 201);
    }

    public function show($id)
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return response()->json(['success' => false, 'message' => 'Policy not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $policy]);
    }

    public function update(Request $request, $id)
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return response()->json(['success' => false, 'message' => 'Policy not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $slug = $policy->title !== $request->title
            ? $this->generateUniqueSlug($request->title, $policy->id)
            : $policy->slug;

        $policy->update([
            'title'   => $request->title,
            'slug'    => $slug,
            'content' => $request->content,
            'status'  => $request->status ?? $policy->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Policy updated successfully.',
            'data'    => $policy,
        ]);
    }

    public function destroy($id)
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return response()->json(['success' => false, 'message' => 'Policy not found.'], 404);
        }

        $policy->delete();

        return response()->json(['success' => true, 'message' => 'Policy deleted successfully.']);
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Policy::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = Policy::where('slug', $slug);
            if ($ignoreId) $query->where('id', '!=', $ignoreId);
        }

        return $slug;
    }
}