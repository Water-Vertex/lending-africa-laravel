<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollateralType;
use App\Models\Collateral;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function index()
    {
        try {
            $collaterals = Collateral::with('collateralType')->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $collaterals
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch collaterals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created collateral.
     */
    public function store(Request $request)
    {
        $request->validate([
            'application_id'         => 'required|integer',
            'collateral_type_id'     => 'required|exists:collateral_types,id',
            'asset_name'             => 'required|string|max:200',
            'estimated_value'        => 'nullable|numeric',
            'ownership_document_no'  => 'nullable|string|max:100',
            'verification_status'    => 'required|in:pending,verified,rejected',
        ]);

        try {
            $collateral = Collateral::create([
                'application_id'        => $request->application_id,
                'collateral_type_id'    => $request->collateral_type_id,
                'asset_name'            => $request->asset_name,
                'estimated_value'       => $request->estimated_value,
                'ownership_document_no' => $request->ownership_document_no,
                'verification_status'   => $request->verification_status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Collateral created successfully',
                'data'    => $collateral->load('collateralType')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create collateral',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified collateral.
     */
    public function show($id)
    {
        try {
            $collateral = Collateral::with('collateralType')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $collateral
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Collateral not found'
            ], 404);
        }
    }

    /**
     * Update the specified collateral.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'application_id'         => 'required|integer',
            'collateral_type_id'     => 'required|exists:collateral_types,id',
            'asset_name'             => 'required|string|max:200',
            'estimated_value'        => 'nullable|numeric',
            'ownership_document_no'  => 'nullable|string|max:100',
            'verification_status'    => 'required|in:pending,verified,rejected',
        ]);

        try {
            $collateral = Collateral::findOrFail($id);
            $collateral->update([
                'application_id'        => $request->application_id,
                'collateral_type_id'    => $request->collateral_type_id,
                'asset_name'            => $request->asset_name,
                'estimated_value'       => $request->estimated_value,
                'ownership_document_no' => $request->ownership_document_no,
                'verification_status'   => $request->verification_status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Collateral updated successfully',
                'data'    => $collateral->load('collateralType')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update collateral',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified collateral.
     */
    public function destroy($id)
    {
        try {
            $collateral = Collateral::findOrFail($id);
            $collateral->delete();

            return response()->json([
                'success' => true,
                'message' => 'Collateral deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete collateral',
                'error'   => $e->getMessage()
            ], 500);
        }
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