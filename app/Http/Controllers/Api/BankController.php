<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function index()
    {
        try {
            $banks = Bank::orderBy('name', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $banks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch banks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|min:2|max:255|unique:banks,name',
            'code'   => 'required|string|max:50|unique:banks,code',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bank = Bank::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Bank added successfully',
                'data' => $bank
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $bank = Bank::find($id);

        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'Bank not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $bank
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $bank = Bank::find($id);

        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'Bank not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|min:2|max:255|unique:banks,name,' . $bank->id,
            'code'   => 'required|string|max:50|unique:banks,code,' . $bank->id,
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bank->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Bank updated successfully',
                'data' => $bank
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $bank = Bank::find($id);

        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'Bank not found'
            ], 404);
        }

        try {
            $bank->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bank deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}