<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoanAgreementTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoanAgreementTemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = LoanAgreementTemplate::latest('id');

        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->input('loan_type'));
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
        ]);
    }

    public function show($id): JsonResponse
    {
        $agreement = LoanAgreementTemplate::find($id);

        if (! $agreement) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $agreement]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'loan_type'   => ['required', Rule::in(LoanAgreementTemplate::LOAN_TYPES)],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $agreement = LoanAgreementTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agreement saved successfully.',
            'data'    => $agreement,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $agreement = LoanAgreementTemplate::find($id);

        if (! $agreement) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'loan_type'   => ['required', Rule::in(LoanAgreementTemplate::LOAN_TYPES)],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $agreement->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agreement updated successfully.',
            'data'    => $agreement->fresh(),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $agreement = LoanAgreementTemplate::find($id);

        if (! $agreement) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $agreement->delete();

        return response()->json(['success' => true, 'message' => 'Agreement deleted successfully.']);
    }
}