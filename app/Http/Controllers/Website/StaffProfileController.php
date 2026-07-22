<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StaffProfileController extends Controller
{
    /**
     * Show the staff profile settings page.
     */
    public function show(): \Illuminate\View\View
    {
        $staff = Auth::guard('staff')->user()->load('bank');

        return view('user.pages.staff.profile-setting', compact('staff'));
    }

    /**
     * Update the staff profile.
     * staff_code, bank_id and status can NEVER be changed here.
     */
    public function update(Request $request): RedirectResponse
    {
        $staff = Auth::guard('staff')->user();

        $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('staff', 'email')->ignore($staff->id),
            ],
            'phone'       => 'nullable|string|max:20',
            'branch_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:100',
        ]);

        $staff->update([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'branch_name' => $request->branch_name,
            'designation' => $request->designation,
            'employee_id' => $request->employee_id,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}