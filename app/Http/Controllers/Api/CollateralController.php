<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollateralType;
use App\Models\Collateral;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Models\LoanApplication;
class CollateralController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | COLLATERALS (collaterals table) - Default/Primary methods
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of collaterals.
     */
   public function index(Request $request): JsonResponse
    {
        $query = Collateral::with(['loanApplication.customer', 'collateralType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('asset_name', 'like', "%{$search}%");
        }

        $collaterals = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $collaterals,
        ]);
    }

    /**
     * List loan applications for the dropdown (id + application_no + customer name).
     */
    public function loanApplicationsList(): JsonResponse
    {
        $applications = LoanApplication::with('customer')
            ->latest()
            ->get()
            ->map(function ($app) {
                return [
                    'id'             => $app->id,
                    'application_no' => $app->application_no,
                    'customer_name'  => $app->customer?->full_name ?? 'N/A',
                    'loan_amount'    => $app->loan_amount,
                    'status'         => $app->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $applications,
        ]);
    }

    /**
     * List collateral types for the dropdown.
     */
    public function collateralTypesList(): JsonResponse
    {
        $types = CollateralType::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data'    => $types,
        ]);
    }

    /**
     * Store a new collateral.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id'          => ['required', 'integer', 'exists:loan_applications,id'],
            'collateral_type_id'      => ['required', 'integer', 'exists:collateral_types,id'],
            'asset_name'              => ['required', 'string', 'max:200'],
            'estimated_value'         => ['nullable', 'numeric', 'min:0'],
            'ownership_document_no'   => ['nullable', 'string', 'max:100'],
            'verification_status'     => ['required', Rule::in(Collateral::VERIFICATION_STATUSES)],
        ]);

        try {
            $collateral = Collateral::create($validated);

            return response()->json([
                'success' => true,
                'data'    => $collateral->load(['loanApplication', 'collateralType']),
                'message' => 'Collateral created successfully.',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create collateral.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show a single collateral.
     */
    public function show(Collateral $collateral): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $collateral->load(['loanApplication.customer', 'collateralType']),
        ]);
    }

    /**
     * Update a collateral.
     */
    public function update(Request $request, Collateral $collateral): JsonResponse
    {
        $validated = $request->validate([
            'application_id'          => ['required', 'integer', 'exists:loan_applications,id'],
            'collateral_type_id'      => ['required', 'integer', 'exists:collateral_types,id'],
            'asset_name'              => ['required', 'string', 'max:200'],
            'estimated_value'         => ['nullable', 'numeric', 'min:0'],
            'ownership_document_no'   => ['nullable', 'string', 'max:100'],
            'verification_status'     => ['required', Rule::in(Collateral::VERIFICATION_STATUSES)],
        ]);

        $collateral->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $collateral->fresh()->load(['loanApplication', 'collateralType']),
            'message' => 'Collateral updated successfully.',
        ]);
    }

    /**
     * Delete a collateral.
     */
    public function destroy(Collateral $collateral): JsonResponse
    {
        $collateral->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collateral deleted successfully.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COLLATERAL TYPES (collateral_types table) - Named methods
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of collateral types.
     */
    public function typesIndex()
    {
        try {
            $types = CollateralType::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $types
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch collateral types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created collateral type.
     */
    public function typesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:collateral_types,name|max:255',
        ]);

        try {
            $type = CollateralType::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Collateral type created successfully',
                'data'    => $type
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create collateral type',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified collateral type.
     */
    public function typesShow($id)
    {
        try {
            $type = CollateralType::findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $type
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Collateral type not found'
            ], 404);
        }
    }

    /**
     * Update the specified collateral type.
     */
    public function typesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('collateral_types')->ignore($id)
            ],
        ]);

        try {
            $type = CollateralType::findOrFail($id);
            $type->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Collateral type updated successfully',
                'data'    => $type
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update collateral type',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified collateral type.
     */
    public function typesDestroy($id)
    {
        try {
            $type = CollateralType::findOrFail($id);
            $type->delete();

            return response()->json([
                'success' => true,
                'message' => 'Collateral type deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete collateral type',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}