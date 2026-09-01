<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     */
    public function index()
    {
        try {
            $faqs = Faq::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $faqs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch FAQs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created FAQ.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            $faq = Faq::create([
                'question'   => $request->question,
                'answer'     => $request->answer,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully',
                'data'    => $faq
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create FAQ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified FAQ.
     */
    public function show($id)
    {
        try {
            $faq = Faq::findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $faq
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found'
            ], 404);
        }
    }

    /**
     * Update the specified FAQ.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            $faq = Faq::findOrFail($id);
            $faq->update([
                'question'   => $request->question,
                'answer'     => $request->answer,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully',
                'data'    => $faq
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update FAQ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified FAQ.
     */
    public function destroy($id)
    {
        try {
            $faq = Faq::findOrFail($id);
            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FAQ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}